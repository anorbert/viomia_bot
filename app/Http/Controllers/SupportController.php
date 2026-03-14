<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Message;

class SupportController extends Controller
{
    /**
     * Display the support form
     */
    public function create()
    {
        // Require logged-in user
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to submit a support request.');
        }

        return view('support.form');
    }

    /**
     * Send support email
     */
    public function store(Request $request)
    {
        // Require logged-in user
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit a support request.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,account,trading,general',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|min:10|max:2000',
            'attachment' => 'nullable|file|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        try {
            // Step 1: Save support ticket to database FIRST
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('support-attachments', 'public');
            }

            $reference = SupportTicket::generateReference();
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'reference_id' => $reference,
                'subject' => $validated['subject'],
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'message' => $validated['message'],
                'attachment_path' => $attachmentPath,
                'status' => 'open',
            ]);

            // Step 2: Send email to support team
            Mail::send('emails.support-ticket', [
                'user' => $user,
                'subject' => $validated['subject'],
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'message' => $validated['message'],
                'timestamp' => $ticket->created_at,
                'reference' => $reference,
            ], function (Message $mail) use ($user, $validated, $attachmentPath) {
                $mail->from(config('mail.from.address'), config('mail.from.name'))
                     ->to(config('mail.support_email', 'geniussoftware.rw@gmail.com'))
                     ->replyTo($user->email)
                     ->subject("[{$validated['priority']}] Support Request: " . $validated['subject']);
                
                // Add attachment if exists
                if ($attachmentPath) {
                    $fullPath = storage_path('app/public/' . $attachmentPath);
                    $mail->attach($fullPath, [
                        'as' => basename($attachmentPath),
                    ]);
                }
            });

            // Step 3: Send confirmation email to user
            Mail::send('emails.support-confirmation', [
                'user' => $user,
                'subject' => $validated['subject'],
                'reference' => $reference,
            ], function (Message $mail) use ($user) {
                $mail->from(config('mail.from.address'), config('mail.from.name'))
                     ->to($user->email)
                     ->subject('Support Request Received - We will get back to you soon');
            });

            return redirect()->route('support.create')
                ->with('success', "Your support request has been submitted successfully! Reference ID: {$reference}. We will respond within 24 hours.");

        } catch (\Exception $e) {
            \Log::error('Support ticket creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to submit support request. Please try again later.');
        }
    }

    /**
     * Display support dashboard for staff (future feature)
     */
    public function index()
    {
        // TODO: Admin/staff can view all support tickets
        abort(403);
    }

    /**
     * Display user's support tickets
     */
    public function userTickets()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tickets = Auth::user()->supportTickets()->latest()->paginate(10);
        return view('support.tickets', compact('tickets'));
    }
}
