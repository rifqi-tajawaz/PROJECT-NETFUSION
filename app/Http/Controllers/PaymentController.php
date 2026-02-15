<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TrialClaim;
use App\Models\User;
use App\Services\Payment\MidtransService;
use App\Services\Payment\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;
    protected SubscriptionService $subscriptionService;

    public function __construct(MidtransService $midtransService, SubscriptionService $subscriptionService)
    {
        $this->midtransService = $midtransService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Initiate Checkout Process (Midtrans Snap)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic_monthly,basic_yearly,premium_monthly,premium_yearly',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $plan = $request->plan;

        // Configuration for Plans (Could be moved to Config/DB in future)
        $plans = [
            'basic_monthly' => ['amount' => 50000, 'name' => 'Basic Monthly', 'duration' => 'monthly'],
            'basic_yearly' => ['amount' => 500000, 'name' => 'Basic Yearly', 'duration' => 'yearly'],
            'premium_monthly' => ['amount' => 150000, 'name' => 'Premium Monthly', 'duration' => 'monthly'],
            'premium_yearly' => ['amount' => 1500000, 'name' => 'Premium Yearly', 'duration' => 'yearly'],
        ];

        if (!isset($plans[$plan])) {
            return back()->with('error', 'Invalid plan selected.');
        }

        $selectedPlan = $plans[$plan];
        $orderId = 'INV-' . time() . '-' . $user->id;

        // 1. Prepare Midtrans Params
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $selectedPlan['amount'],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $plan,
                    'price' => (int) $selectedPlan['amount'],
                    'quantity' => 1,
                    'name' => $selectedPlan['name'],
                ]
            ],
            'credit_card' => ['secure' => true],
        ];

        // 2. Call Service
        $snapResponse = $this->midtransService->createSnapTransaction($params);

        if (!$snapResponse || !isset($snapResponse['redirect_url'])) {
            return back()->with('error', 'Failed to initiate payment. Please try again.');
        }

        // 3. Save Transaction
        Transaction::create([
            'user_id' => $user->id,
            'external_id' => $orderId,
            'amount' => $selectedPlan['amount'],
            'status' => 'PENDING',
            'package_name' => $selectedPlan['name'],
            'duration' => $selectedPlan['duration'],
            'checkout_url' => $snapResponse['redirect_url'],
        ]);

        return redirect($snapResponse['redirect_url']);
    }

    /**
     * Handle Midtrans Webhook
     */
    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Callback:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId)
            return response()->json(['message' => 'Invalid payload'], 400);

        // Verify Signature
        if (!$this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("Midtrans Invalid Signature for Order: $orderId");
            // return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transaction = Transaction::where('external_id', $orderId)->first();
        if (!$transaction)
            return response()->json(['message' => 'Transaction not found'], 404);
        if ($transaction->status === 'PAID')
            return response()->json(['message' => 'Already paid'], 200);

        // Determine Status
        $newStatus = $this->midtransService->normalizeStatus(
            $payload['transaction_status'] ?? '',
            $payload['fraud_status'] ?? ''
        );

        // Update Transaction
        $transaction->update([
            'status' => $newStatus,
            'payment_channel' => $payload['payment_type'] ?? null,
            'payment_method' => $payload['payment_type'] ?? null,
            'paid_at' => $newStatus === 'PAID' ? Carbon::now() : null,
        ]);

        // Activate Membership if PAID
        if ($newStatus === 'PAID') {
            $this->subscriptionService->activateMembership(
                $transaction->user,
                $transaction->package_name,
                $transaction->duration
            );
        }

        return response()->json(['message' => 'Success']);
    }

    public function success()
    {
        return redirect()->route('pricing')->with('success', 'Payment Successful! Your plan is active.');
    }

    public function failed()
    {
        return redirect()->route('pricing')->with('error', 'Payment Failed or Cancelled.');
    }

    /**
     * Start Free Trial
     */
    public function startTrial(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before starting the free trial.');
        }

        if ($user->trial_used_at) {
            return redirect()->route('pricing')->with('error', 'You have already used your free trial.');
        }

        $ipAddress = $request->ip();
        $existingClaim = TrialClaim::where('ip_address', $ipAddress)->first();

        if ($existingClaim) {
            return redirect()->route('pricing')->with('error', 'Free trial has already been claimed from this network/device.');
        }

        // Activate Trial
        $this->subscriptionService->activateTrial($user);

        // Record Claim
        TrialClaim::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
        ]);

        return redirect()->route('mikrotik-suite.dashboard')->with('success', 'Free trial activated! Enjoy Premium access for 3 days.');
    }
}
