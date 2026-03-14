@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3" style="color: #2A3F54; font-weight: 700;">My Support Tickets</h1>
            <p style="color: #8a939f; margin-top: 5px;">Track and manage your support requests</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('support.create') }}" class="btn" style="background: #1ABB9C; color: white; border-radius: 6px; padding: 10px 20px; text-decoration: none; font-weight: 600;">
                <i class="fa fa-plus"></i> New Ticket
            </a>
        </div>
    </div>

    @if($tickets->count() > 0)
        <!-- Tickets List -->
        <div class="row">
            <div class="col-12">
                @foreach($tickets as $ticket)
                    <div class="card" style="margin-bottom: 15px; border-radius: 10px; border: 1px solid #e0e0e0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <!-- Left: Ticket Info -->
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start gap-3">
                                        <!-- Status Badge -->
                                        <div>
                                            @php
                                                $statusColor = match($ticket->status) {
                                                    'open' => '#0066cc',
                                                    'in_progress' => '#ff9800',
                                                    'resolved' => '#28a745',
                                                    default => '#6c757d'
                                                };
                                                $statusLabel = match($ticket->status) {
                                                    'open' => '🔵 Open',
                                                    'in_progress' => '🟡 In Progress',
                                                    'resolved' => '✅ Resolved',
                                                    default => ucfirst($ticket->status)
                                                };
                                            @endphp
                                            <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 6px; font-size: 0.85em; font-weight: 600;">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        <!-- Ticket Details -->
                                        <div style="flex: 1;">
                                            <h5 style="color: #2A3F54; font-weight: 700; margin-bottom: 8px;">
                                                {{ $ticket->subject }}
                                            </h5>
                                            <div style="display: flex; gap: 20px; margin-bottom: 8px; font-size: 0.9em;">
                                                <span style="color: #8a939f;">
                                                    <strong>Reference:</strong> 
                                                    <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 4px;">{{ $ticket->reference_id }}</code>
                                                </span>
                                                <span style="color: #8a939f;">
                                                    <strong>Category:</strong> 
                                                    {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                                </span>
                                                <span style="color: #8a939f;">
                                                    <strong>Priority:</strong>
                                                    @php
                                                        $priorityColor = match($ticket->priority) {
                                                            'high' => '#dc3545',
                                                            'medium' => '#ff9800',
                                                            'low' => '#28a745',
                                                            default => '#6c757d'
                                                        };
                                                    @endphp
                                                    <span style="color: {{ $priorityColor }}; font-weight: 600;">{{ ucfirst($ticket->priority) }}</span>
                                                </span>
                                            </div>
                                            <p style="color: #8a939f; font-size: 0.85em; margin-bottom: 0;">
                                                Submitted: {{ $ticket->created_at->format('M d, Y H:i A') }}
                                                @if($ticket->resolved_at)
                                                    | Resolved: {{ $ticket->resolved_at->format('M d, Y H:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Actions -->
                                <div class="col-md-4 text-end">
                                    <button type="button" class="btn btn-sm" style="background: #f0f0f0; color: #2A3F54; border: none; border-radius: 6px; padding: 8px 16px; cursor: pointer; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="fa fa-eye"></i> View Details
                                    </button>
                                    @if($ticket->attachment_path)
                                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" class="btn btn-sm" style="background: #f0f0f0; color: #2A3F54; border: none; border-radius: 6px; padding: 8px 16px; margin-left: 5px; text-decoration: none; font-weight: 600;" target="_blank">
                                            <i class="fa fa-download"></i> Attachment
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details Modal -->
                    <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="border-radius: 10px; border: 1px solid #e0e0e0;">
                                <div class="modal-header" style="border-bottom: 1px solid #e0e0e0; background: #f9f9f9;">
                                    <h5 class="modal-title" style="color: #2A3F54; font-weight: 700;">
                                        {{ $ticket->subject }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <!-- Status & Priority -->
                                    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                                        @php
                                            $statusColor = match($ticket->status) {
                                                'open' => '#0066cc',
                                                'in_progress' => '#ff9800',
                                                'resolved' => '#28a745',
                                                default => '#6c757d'
                                            };
                                            $priorityColor = match($ticket->priority) {
                                                'high' => '#dc3545',
                                                'medium' => '#ff9800',
                                                'low' => '#28a745',
                                                default => '#6c757d'
                                            };
                                        @endphp
                                        <div>
                                            <strong style="color: #8a939f;">Status</strong>
                                            <div style="margin-top: 5px; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 6px; font-weight: 600; font-size: 0.9em; display: inline-block;">
                                                {{ $ticket->getStatusLabelAttribute() }}
                                            </div>
                                        </div>
                                        <div>
                                            <strong style="color: #8a939f;">Priority</strong>
                                            <div style="margin-top: 5px; padding: 6px 12px; background: {{ $priorityColor }}20; color: {{ $priorityColor }}; border-radius: 6px; font-weight: 600; font-size: 0.9em; display: inline-block;">
                                                {{ $ticket->getPriorityLabelAttribute() }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details Grid -->
                                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                        <div class="row" style="margin: 0;">
                                            <div class="col-md-6 mb-3" style="padding: 0 10px;">
                                                <strong style="color: #8a939f; font-size: 0.85em; text-transform: uppercase;">Reference ID</strong>
                                                <p style="margin: 5px 0; color: #2A3F54; font-weight: 600;">{{ $ticket->reference_id }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3" style="padding: 0 10px;">
                                                <strong style="color: #8a939f; font-size: 0.85em; text-transform: uppercase;">Category</strong>
                                                <p style="margin: 5px 0; color: #2A3F54; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3" style="padding: 0 10px;">
                                                <strong style="color: #8a939f; font-size: 0.85em; text-transform: uppercase;">Submitted</strong>
                                                <p style="margin: 5px 0; color: #2A3F54;">{{ $ticket->created_at->format('M d, Y - H:i A') }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3" style="padding: 0 10px;">
                                                <strong style="color: #8a939f; font-size: 0.85em; text-transform: uppercase;">Last Updated</strong>
                                                <p style="margin: 5px 0; color: #2A3F54;">{{ $ticket->updated_at->format('M d, Y - H:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div>
                                        <strong style="color: #2A3F54; display: block; margin-bottom: 10px;">Your Message</strong>
                                        <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid #1ABB9C; line-height: 1.6; color: #2A3F54; white-space: pre-wrap;">
                                            {{ $ticket->message }}
                                        </div>
                                    </div>

                                    @if($ticket->attachment_path)
                                        <div style="margin-top: 20px;">
                                            <strong style="color: #2A3F54; display: block; margin-bottom: 10px;">Attachment</strong>
                                            <a href="{{ asset('storage/' . $ticket->attachment_path) }}" class="btn" style="background: #1ABB9C; color: white; border-radius: 6px; padding: 8px 16px; text-decoration: none; font-weight: 600; display: inline-block;" target="_blank">
                                                <i class="fa fa-download"></i> Download Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer" style="border-top: 1px solid #e0e0e0; background: #f9f9f9;">
                                    <button type="button" class="btn" style="background: #f0f0f0; color: #2A3F54; border: none; border-radius: 6px; padding: 8px 20px; cursor: pointer; font-weight: 600;" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <a href="https://wa.me/0787373722?text=Reference:%20{{ $ticket->reference_id }}" class="btn" style="background: #25d366; color: white; border-radius: 6px; padding: 8px 20px; text-decoration: none; font-weight: 600;" target="_blank">
                                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card" style="border-radius: 10px; border: 1px solid #e0e0e0; padding: 60px 20px; text-align: center;">
            <div style="font-size: 3em; margin-bottom: 20px; color: #1ABB9C;">📭</div>
            <h4 style="color: #2A3F54; font-weight: 700; margin-bottom: 10px;">No Support Tickets Yet</h4>
            <p style="color: #8a939f; margin-bottom: 20px;">You haven't submitted any support tickets. If you need help, please submit a new ticket.</p>
            <a href="{{ route('support.create') }}" class="btn" style="background: #1ABB9C; color: white; border-radius: 6px; padding: 10px 25px; text-decoration: none; font-weight: 600; display: inline-block;">
                <i class="fa fa-plus"></i> Submit a New Ticket
            </a>
        </div>
    @endif

    <!-- Help Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card" style="border-radius: 10px; border: 1px solid #e0e0e0; background: linear-gradient(135deg, #f5fbf9 0%, #f0f8f7 100%); padding: 30px;">
                <h5 style="color: #2A3F54; font-weight: 700; margin-bottom: 15px;">Need Immediate Help?</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p style="color: #8a939f; margin-bottom: 10px;">For urgent issues, reach out via WhatsApp:</p>
                        <a href="https://wa.me/0787373722?text=Urgent%20support%20needed" class="btn" style="background: #25d366; color: white; border-radius: 6px; padding: 10px 20px; text-decoration: none; font-weight: 600; display: inline-block;" target="_blank">
                            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                        </a>
                    </div>
                    <div class="col-md-6">
                        <p style="color: #8a939f; margin-bottom: 10px;">Or submit a new ticket:</p>
                        <a href="{{ route('support.create') }}" class="btn" style="background: #1ABB9C; color: white; border-radius: 6px; padding: 10px 20px; text-decoration: none; font-weight: 600; display: inline-block;">
                            <i class="fa fa-plus"></i> New Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        background: white;
    }
    
    .btn:hover {
        opacity: 0.9;
        transition: all 0.3s ease;
    }

    code {
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }

    @media (max-width: 768px) {
        .col-md-8, .col-md-4 {
            margin-bottom: 15px;
        }
        
        .col-md-4.text-end {
            text-align: left !important;
        }
        
        .btn-sm {
            font-size: 0.8em !important;
        }
    }
</style>

<!-- Bootstrap JS for modals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
