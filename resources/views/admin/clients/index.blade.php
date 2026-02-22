@extends('layouts.admin')

@section('content')
<div class="container-fluid py-2"> {{-- Reduced padding --}}

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 font-weight-bold text-dark">Client Management</h5>
            <div class="text-muted" style="font-size: 0.7rem;">Monitor client bot activity, trading accounts, and subscription status.</div>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn text-white btn-sm shadow-sm border-0" 
           style="background: linear-gradient(45deg, #1e7e34, #28a745); font-weight: 500; font-size: 0.75rem; padding: 5px 12px;">
            <i class="fa fa-plus-circle mr-1"></i> Add Client
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="clientsTable" style="width:100%">
                    <thead class="bg-light" style="border-bottom: 1px solid #eee;">
                        <tr>
                            <th class="text-uppercase font-weight-bold text-secondary px-3 py-2" style="width: 40px; font-size: 0.65rem;">S/N</th>
                            <th class="text-uppercase font-weight-bold text-secondary" style="font-size: 0.65rem;">Client & Subscription</th>
                            <th class="text-uppercase font-weight-bold text-secondary text-center" style="font-size: 0.65rem;">Bots</th>
                            <th class="text-uppercase font-weight-bold text-secondary" style="font-size: 0.65rem;">Linked Accounts (Platform | Login | Status)</th>
                            <th class="text-uppercase font-weight-bold text-secondary text-center" style="font-size: 0.65rem;">Status</th>
                            <th class="text-uppercase font-weight-bold text-secondary text-right px-3" style="font-size: 0.65rem;">Management</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white" style="font-size: 0.8rem;">
                        @foreach($clients as $key => $client)
                        <tr style="border-bottom: 1px solid #f8f9fa;">
                            <td class="px-3 text-muted font-weight-bold" style="font-size: 0.75rem;">{{ $key + 1 }}</td>
                            
                            {{-- Client Identity & Plan --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative d-inline-block mr-2">
                                        @if($client->profile_photo)
                                            <img src="{{ asset('storage/' . $client->profile_photo) }}" 
                                                 class="rounded-circle shadow-sm border border-white" 
                                                 style="width:32px; height:32px; object-fit:cover; border-width: 1px !important;">
                                        @else
                                            <div class="rounded-circle shadow-sm border border-white d-flex align-items-center justify-content-center bg-light" 
                                                 style="width:32px; height:32px; border-width: 1px !important;">
                                                 <img src="{{ asset('img/bot_logo.png') }}" style="width:18px; opacity: 0.7;">
                                            </div>
                                        @endif

                                        @if($client->is_active)
                                            <span class="position-absolute border border-white rounded-circle bg-success" 
                                                  style="width: 8px; height: 8px; bottom: 1px; right: 1px; border-width: 1.5px !important;"></span>
                                        @endif
                                    </div>
                                    <div style="line-height: 1.1;">
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 0.85rem;">{{ $client->name }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem;">{{ $client->email }}</div>
                                        
                                        {{-- Subscription Detail --}}
                                        @if($client->currentSubscription)
                                            <div class="d-flex align-items-center mt-2" style="gap: 6px;">
    {{-- Plan Badge --}}
    <span class="badge d-flex align-items-center px-2 py-1" 
          style="background-color: #eef2ff; color: #4338ca; border: 1px solid #e0e7ff; font-size: 0.65rem; font-weight: 700; border-radius: 4px; letter-spacing: 0.3px;">
        <i class="fa fa-shield mr-1" style="font-size: 0.6rem;"></i>
        {{ strtoupper($client->currentSubscription->plan->name ?? 'Premium') }}
    </span>

    {{-- Expiration Date --}}
    <span class="text-muted d-flex align-items-center" style="font-size: 0.62rem; font-weight: 500;">
        <i class="fa fa-calendar-o mr-1" style="opacity: 0.6;"></i>
        Exp: {{ $client->currentSubscription->ends_at->format('d M, Y') }}
    </span>
</div>
                                        @else
                                            <div class="text-danger mt-1" style="font-size: 0.65rem;">
                                                <i class="fa fa-warning mr-1"></i>No Active Plan
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Bots Badge --}}
                            <td class="text-center">
                                <span class="badge badge-pill badge-light border text-primary px-2 py-1" style="font-size: 0.65rem;">
                                    <i class="fa fa-android mr-1"></i> {{ $client->bots_count ?? '0' }}
                                </span>
                            </td>
                            
                            {{-- DETAILED ACCOUNTS DISPLAY --}}
                            <td style="min-width: 420px; padding-top: 5px; padding-bottom: 5px;">
                                <div class="d-flex flex-column" style="gap: 6px;">
                                    @forelse($client->accounts as $acc)
                                        @php
                                            // Determine colors based on account type
                                            $isReal = strtolower($acc->account_type) === 'real';
                                            $themeColor = $isReal ? '#28a745' : '#17a2b8'; // Green for Real, Teal/Blue for Demo
                                        @endphp
                                        <div class="d-flex align-items-center px-2 py-1 rounded bg-white shadow-xs" 
                                             style="border-left: 4px solid {{ $themeColor }}; border-bottom: 1px solid #f1f1f1; font-size: 0.75rem;">
                                            
                                            {{-- Platform & Type --}}
                                            <div style="flex: 1.2; line-height: 1;">
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bold text-dark mr-1" style="font-size: 0.75rem;">{{ strtoupper($acc->platform) }}</span>
                                                    <span class="badge {{ $isReal ? 'badge-success' : 'badge-info' }}" style="font-size: 0.55rem; padding: 1px 4px; border-radius: 4px;">
                                                        {{ ucfirst($acc->account_type) }}
                                                    </span>
                                                </div>
                                                <div class="text-muted mt-1" style="font-size: 0.6rem;">
                                                    <i class="fa fa-server mr-1 opacity-50"></i>{{ $acc->server }}
                                                </div>
                                            </div>

                                            <div style="width: 1px; height: 20px; background: #eee; margin: 0 12px;"></div>

                                            {{-- Login ID --}}
                                            <div style="flex: 1;" class="text-center">
                                                <span class="font-weight-bold px-2 py-1 rounded" style="background: #f8f9fa; color: #333; font-size: 0.8rem; letter-spacing: 0.5px;">
                                                    {{ $acc->login }}
                                                </span>
                                            </div>

                                            <div style="width: 1px; height: 20px; background: #eee; margin: 0 12px;"></div>

                                            {{-- Status Indicators --}}
                                            <div style="flex: 1.8;" class="d-flex justify-content-end align-items-center">
                                                <span class="{{ $acc->active ? 'text-success' : 'text-danger' }} font-weight-bold mr-3" style="font-size: 0.7rem;">
                                                    <i class="fa fa-circle mr-1" style="font-size: 7px;"></i>{{ $acc->active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="{{ $acc->connected == 1 ? 'text-success' : 'text-muted' }}" style="font-size: 0.7rem;">
                                                    <i class="fa {{ $acc->connected == 1 ? 'fa-bolt' : 'fa-plug' }} mr-1"></i>
                                                    <span class="{{ $acc->connected == 1 ? 'font-weight-bold' : '' }}">
                                                        {{ $acc->connected == 1 ? 'Connected' : 'Not connected' }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted small italic" style="font-size: 0.65rem; padding-left: 10px;">No linked accounts</div>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Overall Status --}}
                            <td class="text-center">
                                <span class="badge {{ $client->is_active ? 'badge-success' : 'badge-secondary' }} py-1 shadow-xs" style="font-size: 9px; padding: 3px 8px; letter-spacing: 0.5px;">
                                    {{ $client->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="text-right px-3">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.clients.edit', $client->uuid) }}" 
                                       class="btn btn-xs btn-outline-warning mr-1 border-0 shadow-sm" 
                                       style="background-color: #fff9e6; padding: 4px 8px;">
                                        <i class="fa fa-pencil" style="font-size: 0.75rem;"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.clients.destroy', $client->uuid) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-xs border-0 shadow-sm {{ $client->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                style="padding: 4px 8px; {{ $client->is_active ? 'background-color: #fff5f5;' : 'background-color: #f0fff4;' }}"
                                                onclick="return confirm('Change status?')">
                                            <i class="fa {{ $client->is_active ? 'fa-pause' : 'fa-play' }}" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#clientsTable').DataTable({
            "responsive": true,
            "pageLength": 15,
            "columnDefs": [ { "orderable": false, "targets": [3, 5] } ],
            "language": {
                "search": "",
                "searchPlaceholder": "Filter clients...",
                "lengthMenu": "_MENU_"
            }
        });

        $('.dataTables_filter input').addClass('form-control form-control-sm border-0 shadow-sm').css({
            'background': '#f8f9fa',
            'border-radius': '15px',
            'width': '220px',
            'font-size': '0.75rem',
            'padding-left': '15px'
        });
        $('.dataTables_length select').addClass('form-control form-control-sm').css('font-size', '0.75rem');
    });
</script>
@endpush