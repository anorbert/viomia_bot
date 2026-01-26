@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">My Subscription</h4>
      <small class="text-muted">Your current plan & access status</small>
    </div>

    @if(Route::has('user.plans.index'))
      <a href="{{ route('user.plans.index') }}" class="btn btn-outline-primary">
        <i class="fa fa-shopping-cart"></i> View All Plans
      </a>
    @endif
  </div>

  {{-- ================= CURRENT SUBSCRIPTION ================= --}}
  <div class="card shadow-sm mb-3">
    <div class="card-body">

      @if($subscription && $subscription->plan)
        @php
          $plan = $subscription->plan;

          $badge = match($subscription->status){
            'active' => 'bg-success',
            'pending' => 'bg-warning text-dark',
            'expired' => 'bg-secondary',
            'cancelled' => 'bg-danger',
            default => 'bg-info'
          };

          $features = is_array($plan->features) ? $plan->features : [];
        @endphp

        <div class="row align-items-start">
          <div class="col-md-7">
            <h5 class="mb-1">{{ $plan->name }}</h5>

            <div class="mb-2">
              <span class="badge {{ $badge }}">{{ ucfirst($subscription->status) }}</span>
              <span class="ms-2 text-muted">
                {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }} /
                {{ ucfirst($plan->billing_interval) }}
              </span>
            </div>

            @if($plan->description)
              <div class="small text-muted mb-3">{{ $plan->description }}</div>
            @endif

            <div class="row">
              <div class="col-sm-6 mb-2">
                <strong>Started:</strong> {{ optional($subscription->starts_at)->format('Y-m-d H:i') ?? '—' }}
              </div>
              <div class="col-sm-6 mb-2">
                <strong>Expires:</strong> {{ optional($subscription->ends_at)->format('Y-m-d H:i') ?? '—' }}
              </div>
              <div class="col-sm-6 mb-2">
                <strong>Profit Share:</strong> {{ $plan->profit_share }}%
              </div>
              <div class="col-sm-6 mb-2">
                <strong>Reference:</strong> {{ $subscription->reference ?? '—' }}
              </div>
            </div>
          </div>

          <div class="col-md-5">
            <div class="p-3 bg-light rounded">
              <div class="fw-bold mb-2"><i class="fa fa-list"></i> Features</div>

              @if(count($features))
                <ul class="mb-0">
                  @foreach($features as $f)
                    <li class="small">{{ is_array($f) ? json_encode($f) : $f }}</li>
                  @endforeach
                </ul>
              @else
                <div class="text-muted small">No features defined for this plan.</div>
              @endif
            </div>

            <div class="mt-3 d-flex gap-2">
              <button type="button" class="btn btn-outline-primary w-100" data-toggle="modal" data-target="#changePlanModal">
                <i class="fa fa-arrow-up"></i> Upgrade / Change
              </button>

              <button type="button" class="btn btn-outline-secondary w-100" data-toggle="modal" data-target="#renewPlanModal">
                <i class="fa fa-refresh"></i> Renew
              </button>
            </div>

            <div class="mt-2">
              <button type="button" class="btn btn-outline-danger w-100" disabled title="Implement cancel logic when needed">
                <i class="fa fa-ban"></i> Cancel (coming soon)
              </button>
            </div>

          </div>
        </div>

      @else
        <div class="text-center py-4">
          <i class="fa fa-info-circle fa-2x text-muted mb-2"></i>
          <h5 class="mb-2">No active subscription found</h5>
          <p class="text-muted mb-0">
            Choose a plan below and pay via <strong>MoMo</strong> or <strong>Binance</strong> to activate.
          </p>
        </div>
      @endif

    </div>
  </div>

  {{-- ================= PLANS LIST + SUBSCRIBE ================= --}}
  <div class="card shadow-sm">
    <div class="card-header fw-bold">
      <i class="fa fa-shopping-cart"></i> Available Plans
    </div>

    <div class="card-body">
      @if(isset($plans) && $plans->count())
        <div class="row">
          @foreach($plans as $p)
            @php
              $pFeatures = is_array($p->features) ? $p->features : [];
            @endphp

            <div class="col-md-4 mb-3">
              <div class="card h-100 border">
                <div class="card-body d-flex flex-column">
                  <h5 class="mb-1">{{ $p->name }}</h5>
                  <div class="text-muted small mb-2">{{ $p->description ?? '—' }}</div>

                  <div class="mb-2">
                    <span class="fw-bold">{{ $p->currency }} {{ number_format((float)$p->price, 2) }}</span>
                    <span class="text-muted">/ {{ ucfirst($p->billing_interval) }}</span>
                  </div>

                  @if(count($pFeatures))
                    <ul class="small text-muted mb-3">
                      @foreach(array_slice($pFeatures, 0, 4) as $f)
                        <li>{{ is_array($f) ? json_encode($f) : $f }}</li>
                      @endforeach
                      @if(count($pFeatures) > 4)
                        <li class="text-muted">+ {{ count($pFeatures)-4 }} more</li>
                      @endif
                    </ul>
                  @endif

                  <div class="mt-auto">
                    <button
                      type="button"
                      class="btn btn-success w-100 subscribeBtn"
                      data-slug="{{ $p->slug }}"
                      data-name="{{ $p->name }}"
                      data-price="{{ $p->currency }} {{ number_format((float)$p->price, 2) }}"
                    >
                      <i class="fa fa-credit-card"></i> Subscribe
                    </button>
                  </div>

                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-muted">No plans available right now.</div>
      @endif
    </div>
  </div>

</div>

{{-- ================= SUBSCRIBE MODAL ================= --}}
<div class="modal fade" id="subscribeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <form method="POST" id="subscribeForm" action="#">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Subscribe</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <div class="fw-bold" id="planTitle">Plan</div>
            <div class="text-muted small" id="planPrice">Price</div>
          </div>

          <div class="form-group">
            <label class="form-label">Payment Method</label>
            <select name="provider" class="form-control" required>
              <option value="momo">Mobile Money (MoMo)</option>
              <option value="binance">Binance Crypto</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">MoMo Phone (optional)</label>
            <input type="text" name="phone" class="form-control" placeholder="07xxxxxxxx">
            <small class="text-muted">If empty, we will use your account phone number.</small>
          </div>

          <div class="alert alert-info small mb-0">
            After payment confirmation, your subscription activates automatically.
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button class="btn btn-success">
            <i class="fa fa-check"></i> Pay & Activate
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- ================= Optional Modals (Upgrade/Renew) ================= --}}
<div class="modal fade" id="changePlanModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upgrade / Change Plan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="text-muted">Choose another plan from the list below and subscribe. The system will activate the new plan after payment.</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="renewPlanModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Renew Plan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="text-muted">To renew, subscribe to the same plan again and pay. We will extend your access after confirmation.</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- Bootstrap 3/4 compatible JS --}}
<script>
$(document).ready(function () {

  $('.subscribeBtn').on('click', function(){
    const slug  = $(this).data('slug');
    const name  = $(this).data('name');
    const price = $(this).data('price');

    $('#planTitle').text(name);
    $('#planPrice').text(price);

    // Set form action to checkout route for that plan
    const actionUrl = @json(url('/user/checkout')) + '/' + slug; // matches POST /user/checkout/{plan:slug}
    $('#subscribeForm').attr('action', actionUrl);

    $('#subscribeModal').modal('show');
  });

});
</script>
@endsection
