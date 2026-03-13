@extends('layouts.user')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<style>
    .right_col { background: #f3f2ef !important; padding: 12px 20px !important; }
    .ln-card { 
        background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; 
        margin-bottom: 12px; box-shadow: 0 0.1rem 0.3rem rgba(0,0,0,0.05); 
    }

    .table-clean thead th {
        background: #2A3F54;
        padding: 6px 10px !important;
        border-bottom: 1px solid #eee;
        font-size: 11px;
        text-transform: uppercase;
        color: #fff;
    }

    .table-clean td {
        vertical-align: middle !important;
        padding: 5px 10px !important;
        border-top: 1px solid #f3f2ef !important;
        font-size: 12px;
    }

    .badge-status {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 9px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
    }

    .bg-success-light { background-color: #dcfce7; color: #15803d; }
    .bg-danger-light { background-color: #fee2e2; color: #b91c1c; }
</style>

<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8 col-12">
            <h5 class="mb-1" style="font-weight: 700; color: #2A3F54; font-size: 18px;">Weekly Performance Report</h5>
            <p class="text-muted mb-0" style="font-size: 11px;">Weekly profit/loss summary for all weeks</p>
        </div>
    </div>

    {{-- Subscription Payment Alert --}}
    @if($showSubscriptionAlert)
    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" style="background: #fff3cd; border: 1px solid #ffc107;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 20px;">⚠️</span>
            <div>
                <strong style="color: #856404;">Payment Required</strong>
                <p style="color: #856404; margin: 5px 0 0 0; font-size: 12px;">
                    Your subscription requires payment to access trading features. Please complete your payment to continue.
                </p>
            </div>
        </div>
        <a href="{{ route('user.subscriptions.index') }}" class="btn btn-sm btn-warning mt-2" style="font-size: 11px; padding: 5px 12px;">
            <i class="fa fa-money mr-1"></i> Go to Subscriptions
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- No Subscription Alert --}}
    @if(!$userSubscription)
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert" style="background: #d1ecf1; border: 1px solid #bee5eb;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 20px;">ℹ️</span>
            <div>
                <strong style="color: #0c5460;">No Active Subscription</strong>
                <p style="color: #0c5460; margin: 5px 0 0 0; font-size: 12px;">
                    You don't have an active subscription. Please subscribe to a plan to access trading features and view your performance data.
                </p>
            </div>
        </div>
        <a href="{{ route('user.subscriptions.index') }}" class="btn btn-sm btn-info mt-2" style="font-size: 11px; padding: 5px 12px;">
            <i class="fa fa-plus mr-1"></i> View Subscription Plans
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Unpaid Weekly Payments Alert --}}
    @if($showUnpaidWeeklyAlert)
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="background: #f8d7da; border: 1px solid #f5c6cb;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 20px;">💳</span>
            <div>
                <strong style="color: #721c24;">Outstanding Weekly Payments</strong>
                <p style="color: #721c24; margin: 5px 0 0 0; font-size: 12px;">
                    You have {{ $unpaidWeeklyPayments->count() }} unpaid weekly payment(s) from past weeks. Please settle your outstanding balance.
                </p>
            </div>
        </div>
        <table style="margin-top: 12px; font-size: 11px; color: #721c24; width: 100%;">
            <thead style="border-bottom: 1px solid #f5c6cb;">
                <tr>
                    <th style="text-align: left; padding: 4px 0;">Week</th>
                    <th style="text-align: left; padding: 4px 0;">Amount</th>
                    <th style="text-align: left; padding: 4px 0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unpaidWeeklyPayments as $payment)
                <tr style="border-bottom: 1px solid rgba(207, 34, 46, 0.2);">
                    <td style="padding: 6px 0;">{{ $payment->week_start->format('M d') }} - {{ $payment->week_end->format('M d, Y') }}</td>
                    <td style="padding: 6px 0;">USD {{ number_format($payment->amount, 2) }}</td>
                    <td style="padding: 6px 0;"><span style="background: #f5c6cb; padding: 2px 6px; border-radius: 3px;">{{ ucfirst($payment->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; top: 10px; right: 10px;"></button>
    </div>
    @endif

    {{-- Content visible only if subscription exists --}}
    @if($userSubscription && $userSubscription->plan)
    <div class="ln-card" style="padding: 20px; background: linear-gradient(135deg, #2A3F54 0%, #3d5467 100%); border-color: #1a2738; margin-bottom: 20px; border-left: 5px solid #10b981;">
        <div style="margin-bottom: 12px;">
            <h6 style="color: #b0b8c1; font-size: 12px; font-weight: 600; margin-bottom: 0;">current subscription plan</h6>
        </div>
        <div style="display: flex; flex-wrap: nowrap; gap: 25px; align-items: center; overflow-x: auto; padding-bottom: 8px;">
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">plan name</span>
                <p style="font-size: 13px; color: #fff; font-weight: 700; margin: 0;">{{ $userSubscription->plan->name }}</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">status</span>
                <span class="badge badge-status @if($userSubscription->status === 'active') bg-success-light @else bg-danger-light @endif" style="padding: 4px 10px; font-size: 10px;">
                    {{ ucfirst($userSubscription->status) }}
                </span>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">price</span>
                <p style="font-size: 13px; color: #10b981; font-weight: 700; margin: 0;">{{ $userSubscription->plan->currency }} {{ number_format($userSubscription->plan->price, 2) }}</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">profit share</span>
                <p style="font-size: 13px; color: #fff; font-weight: 700; margin: 0;">{{ number_format($userSubscription->plan->profit_share, 2) }}%</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">real accounts</span>
                <p style="font-size: 13px; color: #10b981; font-weight: 700; margin: 0;">{{ $realAccountsCount }}</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">duration</span>
                <p style="font-size: 13px; color: #fff; font-weight: 700; margin: 0;">{{ $userSubscription->plan->duration_days }} days</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">expires on</span>
                <p style="font-size: 13px; color: #fff; font-weight: 700; margin: 0;">{{ $userSubscription->ends_at ? $userSubscription->ends_at->format('M d, Y') : 'Lifetime' }}</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: baseline; white-space: nowrap;">
                <span style="font-size: 11px; color: #7a8fa6; font-weight: 600;">weekly payment</span>
                <p style="font-size: 13px; color: #10b981; font-weight: 700; margin: 0;">
                    @if($isEntryPlan && $paymentAmount > 0)
                        ${{ number_format($paymentAmount, 2) }}
                    @else
                        -
                    @endif
                </p>
            </div>
            @if($isEntryPlan && $paymentAmount > 0)
            <div style="display: flex; gap: 8px; align-items: center; white-space: nowrap;">
                <button id="payNowBtn" style="background: #10b981; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#paymentModal">Pay Now</button>
            </div>
            @endif
        </div>
        @if($userAccounts->count() > 0)
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 12px; margin-top: 12px;">
            <div style="display: flex; flex-wrap: nowrap; gap: 20px; overflow-x: auto; padding-bottom: 4px;">
                @foreach($userAccounts as $account)
                <div style="white-space: nowrap; flex-shrink: 0;">
                    <p style="font-size: 12px; color: #fff; font-weight: 600; margin: 0;">{{ strtoupper($account->platform) }} - {{ $account->login }} | {{ ucfirst($account->account_type) }} | {{ $account->server }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Summary Table --}}
    <div class="ln-card">
        <div style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-clean w-100" id="weeklySummaryTable">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>P&L</th>
                            <th>Profit</th>
                            <th>Loss</th>
                            <th>Trades</th>
                            <th>Win Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($weeklySummaries as $week)
                            @php
                                $pnlClass = $week['netProfit'] >= 0 ? 'text-success' : 'text-danger';
                            @endphp
                            <tr>
                                <td class="font-weight-bold">{{ $week['week'] }}</td>
                                <td class="font-weight-bold {{ $pnlClass }}">
                                    {{ $week['netProfit'] >= 0 ? '+' : '' }}{{ number_format($week['netProfit'], 2) }}
                                </td>
                                <td class="text-success">+{{ number_format($week['profit'], 2) }}</td>
                                <td class="text-danger">-{{ number_format($week['loss'], 2) }}</td>
                                <td class="font-weight-bold">{{ $week['totalTrades'] }}</td>
                                <td class="text-info">{{ number_format($week['winRate'], 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No trading data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="ln-card" style="padding: 10px;">
        <a href="{{ route('user.signals.index') }}" class="btn btn-sm btn-secondary" style="font-size: 12px; padding: 5px 12px;">
            <i class="fa fa-arrow-left mr-1"></i> Back to Trade History
        </a>
    </div>
</div>
@endif

    {{-- Payment Modal --}}
    @if($isEntryPlan && $paymentAmount > 0)
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #f3f2ef;">
            <div class="modal-header" style="background: #2A3F54; color: #fff; border: none;">
                <h5 class="modal-title" id="paymentModalLabel">Weekly Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-radius: 6px; border-left: 4px solid #f59e0b;">
                    <p style="font-size: 12px; color: #7a8fa6; margin-bottom: 4px; font-weight: 600;">PAYMENT AMOUNT (30%)</p>
                    <p style="font-size: 16px; color: #2A3F54; font-weight: 700; margin-bottom: 0;">USD {{ number_format($paymentAmount, 2) }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 10px;">SELECT PAYMENT METHOD</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <label style="flex: 1; cursor: pointer; min-width: 100px;">
                            <input type="radio" name="paymentMethod" value="momo" checked style="margin-right: 5px;">
                            <span style="font-size: 12px; color: #2A3F54; font-weight: 600;">🟠 MOMO</span>
                        </label>
                        <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                            <input type="radio" name="paymentMethod" value="binance" disabled style="margin-right: 5px;">
                            <span style="font-size: 12px; color: #999; font-weight: 600;">🔶 BINANCE</span>
                        </label>
                        <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                            <input type="radio" name="paymentMethod" value="visa" disabled style="margin-right: 5px;">
                            <span style="font-size: 12px; color: #999; font-weight: 600;">💳 VISA</span>
                        </label>
                        <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                            <input type="radio" name="paymentMethod" value="paypal" disabled style="margin-right: 5px;">
                            <span style="font-size: 12px; color: #999; font-weight: 600;">🅿️ PAYPAL</span>
                        </label>
                    </div>
                </div>

                {{-- MOMO Details --}}
                <div id="momoDetails" style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border-radius: 6px; border: 1px solid #ffc107;">
                    <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 8px;">MOMO PHONE NUMBER</label>
                    <input type="text" id="momoPhone" placeholder="e.g., +250 7XX XXX XXX" style="width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px; margin-bottom: 10px;">
                    
                    <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 8px;">ACCOUNT NAME</label>
                    <input type="text" id="momoName" placeholder="Full name on MOMO account" style="width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;">
                </div>

                <div style="padding: 12px; background: #e3f2fd; border-radius: 6px; margin-bottom: 15px;">
                    <p style="font-size: 11px; color: #1565c0; margin: 0;">ℹ️ Select your preferred payment method above and click "Confirm Payment" to proceed.</p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e0e0e0;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 6px 14px;">Cancel</button>
                <button type="button" class="btn btn-sm" id="confirmPaymentBtn" style="background: #10b981; color: #fff; border: none; font-size: 12px; padding: 6px 14px; font-weight: 600;">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#weeklySummaryTable').DataTable({
        "paging": true,
        "pageLength": 10,
        "searching": true,
        "info": true,
        "lengthChange": false,
        "columnDefs": [
            { "orderable": true, "targets": "_all" }
        ]
    });

    // Handle payment method selection
    $('input[name="paymentMethod"]').on('change', function() {
        var method = $(this).val();
        if (method === 'momo') {
            $('#momoDetails').show();
        }
    });

    // Handle payment confirmation
    $('#confirmPaymentBtn').on('click', function() {
        var selectedMethod = $('input[name="paymentMethod"]:checked').val();
        var amount = {{ $paymentAmount }};
        
        if (!selectedMethod) {
            alert('Please select a payment method');
            return;
        }

        if (selectedMethod !== 'momo') {
            alert('This payment method is coming soon. Only MOMO is available now.');
            return;
        }

        var momoPhone = $('#momoPhone').val().trim();
        var momoName = $('#momoName').val().trim();

        if (!momoPhone) {
            alert('Please enter MOMO phone number');
            return;
        }

        if (!momoName) {
            alert('Please enter account name');
            return;
        }

        // Show loading state
        var btn = $(this);
        btn.prop('disabled', true);
        btn.text('Processing...');

        // Send payment request to server
        $.ajax({
            url: '{{ route("user.weekly-payment.store") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                payment_method: selectedMethod,
                amount: amount,
                momo_phone: momoPhone,
                momo_name: momoName
            },
            success: function(response) {
                // Hide modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();

                // Show success message
                alert('Payment initiated successfully!');
                
                // Reload page to see updated payment records
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(error) {
                btn.prop('disabled', false);
                btn.text('Confirm Payment');
                alert('Error processing payment: ' + (error.responseJSON?.message || 'Please try again'));
            }
        });
    });
});
</script>
@endpush
