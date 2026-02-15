<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Stats Calculation
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'critical' => Ticket::where('priority', 'Critical')->count(),
        ];

        // Filtering
        $query = Ticket::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        return view('admin.support.tickets.index', compact('tickets', 'stats'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'replies.user'])->findOrFail($id);
        return view('admin.support.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,svg|max:5120',
        ]);

        $ticket = Ticket::findOrFail($id);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-replies', 'public');
                $attachments[] = $path;
            }
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        // Optionally update ticket status to "In Progress" or "Answered" if needed
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Reply submitted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,closed,pending,in_progress',
            'priority' => 'nullable|in:Low,Medium,High,Critical',
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority ?? $ticket->priority,
        ]);

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.support.tickets.index')->with('success', 'Ticket deleted successfully');
    }
}
