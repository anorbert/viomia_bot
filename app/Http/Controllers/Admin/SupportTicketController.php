<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::with(['user'])->latest()->paginate(15);
        return view('admin.support_tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new support ticket.
     */
    public function create()
    {
        return view('admin.support_tickets.create');
    }

    /**
     * Store a newly created support ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'title'      => 'required|string|max:255',
            'category'   => 'required|in:Technical,Billing,Trading,General',
            'priority'   => 'required|in:Low,Medium,High,Critical',
            'description' => 'required|string|max:5000',
        ]);

        try {
            $ticket = SupportTicket::create([
                'user_id'    => $validated['user_id'],
                'title'      => $validated['title'],
                'category'   => $validated['category'],
                'priority'   => $validated['priority'],
                'description' => $validated['description'],
                'status'     => 'open',
            ]);

            Log::info('Support ticket created', ['ticket_id' => $ticket->id, 'by' => auth()->id()]);

            return redirect()
                ->route('admin.support_tickets.show', $ticket)
                ->with('success', 'Support ticket created successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to create support ticket', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create support ticket.');
        }
    }

    /**
     * Display the specified support ticket.
     */
    public function show(SupportTicket $supportTicket)
    {
        $ticket = $supportTicket->load(['user']);
        return view('admin.support_tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified support ticket.
     */
    public function edit(SupportTicket $supportTicket)
    {
        $ticket = $supportTicket->load(['user']);
        return view('admin.support_tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified support ticket in storage.
     */
    public function update(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'category'   => 'required|in:Technical,Billing,Trading,General',
            'priority'   => 'required|in:Low,Medium,High,Critical',
            'description' => 'required|string|max:5000',
            'status'     => 'required|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        try {
            $supportTicket->update($validated);

            Log::info('Support ticket updated', ['ticket_id' => $supportTicket->id, 'by' => auth()->id()]);

            return redirect()
                ->route('admin.support_tickets.show', $supportTicket)
                ->with('success', 'Support ticket updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to update support ticket', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update support ticket.');
        }
    }

    /**
     * Remove the specified support ticket from storage.
     */
    public function destroy(SupportTicket $supportTicket)
    {
        try {
            $supportTicket->delete();

            Log::info('Support ticket deleted', ['ticket_id' => $supportTicket->id, 'by' => auth()->id()]);

            return redirect()
                ->route('admin.support_tickets.index')
                ->with('success', 'Support ticket deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to delete support ticket', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete support ticket.');
        }
    }
}
