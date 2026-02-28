@extends('layouts.user')

@section('content')
<style>
    /* Dashboard Consistency */
    .right_col { background: #f3f2ef !important; padding: 20px 25px !important; }
    .ln-card { 
        background: #fff; border-radius: 10px; border: 1px solid #e0e0e0; 
        margin-bottom: 25px; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.05); 
    }
    
    /* Subscription Specifics */
    .plan-header { border-bottom: 2px solid #f3f2ef; padding-bottom: 15px; margin-bottom: 20px; }
    .feature-list { list-style: none; padding: 0; }
    .feature-list li { padding: 6px 0; border-bottom: 1px solid #f8f9fa; display: flex; align-items: center; font-size: 13px; }
    .feature-list li i { color: #1ABB9C; margin-right: 10px; width: 15px; }
    
    /* Plan Cards */
    .pricing-card { transition: transform 0.2s; border-radius: 12px; overflow: hidden; }
    .pricing-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .price-tag { font-size: 24px; font-weight: 800; color: #2A3F54; }
    
    /* Badge styling */
    .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .bg-success-light { background-color: #dcfce7; color: #15803d; }
    .bg-warning-light { background-color: #fef9c3; color: #854d0e; }
    .bg-danger-light { background-color: #fee2e2; color: #b91c1c; }
    .bg-gray-light { background-color: #f3f4f6; color: #374151; }
</style>

<div class="container-fluid">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 class="mb-1" style="font-weight: 700; color: #2A3F54;">My Subscription</h3>
            <p class="text-muted mb-0">Manage your billing and bot access permissions.</p>
        </div>
        <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0">
            @if(Route::has('user.plans.index'))
                <a href="{{ route('user.plans.index') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-shopping-cart mr-1"></i> Browse All Plans
                </a>
            @endif
        </div>
    </div>

    {{-- ================= CURRENT SUBSCRIPTION ================= --}}
    <div class="ln-card">
        <div class="card-body p-4">
            @if($subscription && $subscription->plan)
                @php
                    $plan = $subscription->plan;
                    $statusClass = match($subscription->status){
                        'active' => 'bg-success-light',
                        'pending' => 'bg-warning-light',
                        'cancelled' => 'bg-danger-light',
                        default => 'bg-gray-light'
                    };
                    $features = is_array($plan->features) ? $plan->features : [];
                @endphp

                <div class="row">
                    <div class="col-md-7 border-right">
                        <div class="plan-header">
                            <span class="badge-status {{ $statusClass }} mb-2">{{ $subscription->status }}</span>
                            <h2 class="font-weight-bold" style="color: #2A3F54;">{{ $plan->name }}</h2>
                            <p class="text-muted">{{ $plan->description ?? 'Premium trading signals and automated execution.' }}</p>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="small text-uppercase text-muted font-weight-bold mb-0">Started On</label>
                                <div class="font-weight-bold">{{ optional($subscription->starts_at)->format('d M Y, H:i') ?? '—' }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-uppercase text-muted font-weight-bold mb-0">Renewal Date</label>
                                <div class="font-weight-bold text-primary">{{ optional($subscription->ends_at)->format('d M Y, H:i') ?? '—' }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-uppercase text-muted font-weight-bold mb-0">Profit Share</label>
                                <div class="font-weight-bold text-success">{{ $plan->profit_share }}%</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-uppercase text-muted font-weight-bold mb-0">Reference</label>
                                <div class="font-weight-bold">#{{ $subscription->reference ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 pl-md-5">
                        <div class="p-3 rounded" style="background: #f9fafb; border: 1px solid #f0f0f0;">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-check-circle text-success mr-2"></i> Included Features</h6>
                            <ul class="feature-list mb-0">
                                @forelse($features as $f)
                                    <li><i class="fa fa-check"></i> {{ is_array($f) ? 'Custom Feature' : $f }}</li>
                                @empty
                                    <li class="text-muted">Standard access features.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-4 row no-gutters">
                            <div class="col-6 pr-1">
                                <button type="button" class="btn btn-primary btn-block shadow-sm" style="border-radius: 8px;" data-toggle="modal" data-target="#changePlanModal">
                                    Upgrade
                                </button>
                            </div>
                            <div class="col-6 pl-1">
                                <button type="button" class="btn btn-outline-secondary btn-block" style="border-radius: 8px;" data-toggle="modal" data-target="#renewPlanModal">
                                    Renew
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa fa-credit-card-alt fa-3x text-light"></i>
                    </div>
                    <h4 class="font-weight-bold">No Active Subscription</h4>
                    <p class="text-muted mx-auto" style="max-width: 400px;">
                        You currently don't have an active plan. Choose a plan below to unlock automated trading signals via Binance or MoMo.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- ================= PLANS LIST ================= --}}
    <h4 class="mb-4 font-weight-bold" style="color: #2A3F54;">Available Plans</h4>
    <div class="row">
        @forelse($plans as $p)
            @php $pFeatures = is_array($p->features) ? $p->features : []; @endphp
            <div class="col-md-4 mb-4">
                <div class="card pricing-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="font-weight-bold mb-1">{{ $p->name }}</h5>
                        <p class="small text-muted mb-4">{{ Str::limit($p->description, 60) }}</p>

                        <div class="mb-4">
                            <span class="price-tag">{{ $p->currency }} {{ number_format((float)$p->price, 0) }}</span>
                            <span class="text-muted small">/ {{ $p->billing_interval }}</span>
                        </div>

                        <ul class="feature-list mb-4 flex-grow-1">
                            @foreach(array_slice($pFeatures, 0, 5) as $f)
                                <li><i class="fa fa-bolt"></i> {{ is_array($f) ? 'Premium Feature' : $f }}</li>
                            @endforeach
                        </ul>

                        <a type="button" 
                                href="{{ route('user.plans.show', $p->slug) }}"
                                class="btn btn-success btn-block py-2 shadow-sm subscribeBtn"
                                style="border-radius: 8px; font-weight: 700;"
                                data-slug="{{ $p->slug }}"
                                data-name="{{ $p->name }}"
                                data-price="{{ $p->currency }} {{ number_format((float)$p->price, 2) }}">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">No plans available.</div>
        @endforelse
    </div>
</div>

{{-- ================= SUBSCRIBE MODAL (Cleaned Up) ================= --}}
<div class="modal fade" id="subscribeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" id="subscribeForm" action="#">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Complete Subscription</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center p-3 mb-4 rounded" style="background: #f8f9fa; border: 1px solid #eee;">
                        <div>
                            <div class="font-weight-bold" id="planTitle" style="font-size: 16px;"></div>
                            <div class="text-muted small">Subscription Plan</div>
                        </div>
                        <div class="text-primary font-weight-bold" id="planPrice" style="font-size: 18px;"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">PAYMENT METHOD</label>
                        <select name="provider" class="form-control" style="border-radius: 8px;" required>
                            <option value="momo">Mobile Money (MoMo)</option>
                            <option value="binance">Binance Pay (Crypto)</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold small text-muted">MOMO PHONE NUMBER</label>
                        <input type="text" name="phone" class="form-control" style="border-radius: 8px;" placeholder="07xxxxxxxx">
                        <small class="text-muted">Required for MoMo push notifications.</small>
                    </div>

                    <div class="p-3 rounded bg-info-light text-info small" style="background-color: #e0f2fe; color: #0369a1; border-radius: 8px;">
                        <i class="fa fa-info-circle mr-2"></i> Activation is automatic once the transaction is confirmed on the blockchain/network.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">Confirm & Pay</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.subscribeBtn').on('click', function(){
        const slug = $(this).data('slug');
        const name = $(this).data('name');
        const price = $(this).data('price');

        $('#planTitle').text(name);
        $('#planPrice').text(price);
        $('#subscribeForm').attr('action', @json(url('/user/checkout')) + '/' + slug);
        $('#subscribeModal').modal('show');
    });
});
</script>
@endsection