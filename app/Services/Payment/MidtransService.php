<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private $serverKey;
    private $isProduction;

    public function __construct()
    {
        $this->serverKey = env('MIDTRANS_SERVER_KEY');
        $this->isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    }

    public function createSnapTransaction(array $params)
    {
        $apiUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($apiUrl, $params);

        if ($response->failed()) {
            Log::error('Midtrans API Error: ' . $response->body());
            return null;
        }

        return $response->json();
    }

    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return $signatureKey === $expectedSignature;
    }

    public function normalizeStatus(string $transactionStatus, string $fraudStatus = null): string
    {
        if ($transactionStatus == 'capture') {
            return $fraudStatus == 'challenge' ? 'CHALLENGE' : 'PAID';
        } else if ($transactionStatus == 'settlement') {
            return 'PAID';
        } else if ($transactionStatus == 'pending') {
            return 'PENDING';
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
            return 'FAILED';
        } else if ($transactionStatus == 'expire') {
            return 'EXPIRED';
        }
        return 'UNKNOWN';
    }
}
