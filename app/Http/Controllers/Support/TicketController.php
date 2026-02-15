<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Ticket::where('user_id', Auth::id())->count(),
            'open' => Ticket::where('user_id', Auth::id())->whereIn('status', ['open', 'in_progress'])->count(),
            'closed' => Ticket::where('user_id', Auth::id())->where('status', 'closed')->count(),
        ];

        return view('pages.support.index', compact('tickets', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'department' => 'required|string|in:Technical Support,Sales Inquiry,Billing & Account', // Match select options
            'priority' => 'required|string|in:Low,Medium,High',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:5120', // 5MB max
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Store in 'public/tickets'
                $path = $file->store('tickets', 'public');
                $attachments[] = $path;
            }
        }

        Ticket::create([
            'user_id' => Auth::id(), // Null if guest
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'department' => $validated['department'],
            'priority' => $validated['priority'],
            'message' => $validated['message'],
            'attachments' => !empty($attachments) ? $attachments : null,
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'Your ticket has been submitted successfully! We will get back to you soon.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'replies.user'])->findOrFail($id);

        // Ensure user owns the ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        return view('pages.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-replies', 'public');
                $attachments[] = $path;
            }
        }

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }
}
