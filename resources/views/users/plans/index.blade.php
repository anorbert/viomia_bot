@extends('layouts.user')

@section('content')
<div class="container py-5">

  {{-- Header Section --}}
  <div class="mb-5">
    <div class="row align-items-center mb-4">
      <div class="col-lg-8">
        <h2 class="mb-2 fw-bold">
          <i class="fa fa-star text-warning me-2"></i>Subscription Plans
        </h2>
        <p class="text-muted lead mb-0">
          Choose the perfect plan for your trading strategy. Upgrade or downgrade anytime.
        </p>
      </div>
      <div class="col-lg-4 mt-3 mt-lg-0">
        <div class="input-group input-group-lg">
          <span class="input-group-text bg-white border-0">
            <i class="fa fa-search text-muted"></i>
          </span>
          <input id="planSearch" type="text" class="form-control border-0" placeholder="Search plans...">
        </div>
      </div>
    </div>

    {{-- Info Banner --}}
    <div class="alert alert-info border-0 mb-0">
      <div class="d-flex align-items-center">
        <i class="fa fa-info-circle fa-lg me-3"></i>
        <div>
          <strong>All plans include:</strong>
          Trading signals • Market analysis • 24/7 support • Real-time notifications
        </div>
      </div>
    </div>
  </div>

  {{-- Plans Grid --}}
  <div class="row" id="plansGrid" style="margin-bottom: -2rem;">
    @forelse($plans as $plan)
      @php
        $price = (float) $plan->price;
        $isPopular = $plan->sort_order === 1 || $plan->is_featured;
      @endphp

      <div class="col-lg-4 col-md-6 mb-4 plan-item"
           data-name="{{ strtolower($plan->name.' '.$plan->description) }}">

        <div class="card shadow h-100 border-0 position-relative {{ $isPopular ? 'ring-popular' : '' }}"
             style="transition: all 0.3s ease;">

          {{-- Popular Badge --}}
          @if($isPopular)
            <div class="position-absolute top-0 start-50 translate-middle-x">
              <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fa fa-crown me-1"></i>Most Popular
              </span>
            </div>
          @endif

          <div class="card-body p-4 d-flex flex-column h-100 {{ $isPopular ? 'mt-3' : '' }}">

            {{-- Title & Badge --}}
            <div class="mb-4">
              <h5 class="mb-2 fw-bold">
                <i class="fa fa-gem text-primary me-2"></i>{{ $plan->name }}
              </h5>
              <p class="text-muted small mb-0">
                {{ \Illuminate\Support\Str::limit($plan->description, 100) }}
              </p>
            </div>

            {{-- Pricing --}}
            <div class="mb-4 pb-4 border-bottom">
              <div class="d-flex align-items-baseline gap-2 mb-2">
                <span class="display-5 fw-bold text-dark">{{ $plan->currency }}</span>
                <span class="display-5 fw-bold text-primary">{{ number_format($price, 0) }}</span>
              </div>
              <div class="text-muted mb-2">
                <i class="fa fa-clock-o me-1"></i>
                @if($plan->duration_days)
                  {{ $plan->duration_days }} days access
                @else
                  {{ ucfirst($plan->billing_interval) }} billing
                @endif
              </div>
              <small class="text-success">
                <i class="fa fa-check-circle me-1"></i>Instant activation on payment
              </small>
            </div>

            {{-- Features List --}}
            @php $features = is_array($plan->features) ? $plan->features : []; @endphp
            <div class="mb-4 flex-grow-1">
              @if(count($features))
                <div class="small fw-bold mb-3 text-muted">Includes:</div>
                <ul class="list-unstyled">
                  @foreach(array_slice($features, 0, 5) as $f)
                    <li class="mb-2 small">
                      <i class="fa fa-check-circle text-success me-2" style="width: 16px;"></i>
                      {{ is_array($f) ? json_encode($f) : $f }}
                    </li>
                  @endforeach
                  @if(count($features) > 5)
                    <li class="mb-2 small text-muted">
                      <i class="fa fa-plus me-2"></i>
                      {{ count($features) - 5 }} more features
                    </li>
                  @endif
                </ul>
              @else
                <div class="alert alert-light border-0 small mb-0">
                  <i class="fa fa-info-circle me-1"></i> Plan features available on details page.
                </div>
              @endif
            </div>

            {{-- CTA Button --}}
            <a href="{{ route('user.plans.show', $plan->slug) }}" 
               class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline-primary' }} w-100 rounded-2 fw-bold group">
              <i class="fa fa-arrow-right me-2"></i>
              {{ $isPopular ? 'Subscribe Now' : 'View Details' }}
              <i class="fa fa-chevron-right ms-2" style="transition: 0.2s;"></i>
            </a>

          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info border-0 p-5 text-center">
          <div class="mb-3">
            <i class="fa fa-inbox fa-3x text-muted mb-3 d-block"></i>
          </div>
          <h5>No Plans Available</h5>
          <p class="text-muted mb-0">Subscription plans are coming soon. Please check back later.</p>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Additional Info Section --}}
  <div class="row mt-5 pt-5 border-top">
    <div class="col-md-4 text-center mb-4">
      <div class="mb-3">
        <i class="fa fa-lock fa-2x text-primary mb-3 d-block"></i>
      </div>
      <h6 class="fw-bold">100% Secure</h6>
      <p class="text-muted small">All transactions are encrypted and secure.</p>
    </div>
    <div class="col-md-4 text-center mb-4">
      <div class="mb-3">
        <i class="fa fa-exchange fa-2x text-primary mb-3 d-block"></i>
      </div>
      <h6 class="fw-bold">Easy Upgrade</h6>
      <p class="text-muted small">Upgrade or downgrade your plan at any time.</p>
    </div>
    <div class="col-md-4 text-center mb-4">
      <div class="mb-3">
        <i class="fa fa-headphones fa-2x text-primary mb-3 d-block"></i>
      </div>
      <h6 class="fw-bold">24/7 Support</h6>
      <p class="text-muted small">Our support team is always here to help.</p>
    </div>
  </div>

</div>

@push('styles')
<style>
  .ring-popular {
    border: 2px solid #ffc107 !important;
  }

  .plan-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .plan-item:hover .card {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
  }

  .plan-item .card {
    transition: all 0.3s ease;
  }

  .plan-item .btn {
    transition: all 0.3s ease;
  }

  .plan-item:hover .btn {
    transform: translateX(4px);
  }

  .plan-item:hover .btn i:last-child {
    transform: translateX(2px);
  }

  .input-group-text {
    border-radius: 0.5rem 0 0 0.5rem !important;
  }

  .form-control {
    border-radius: 0 0.5rem 0.5rem 0 !important;
    padding: 0.75rem 1.25rem;
  }

  .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
  }

  @media (max-width: 768px) {
    .plan-item:hover .card {
      transform: none;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  (function () {
    var input = document.getElementById('planSearch');
    if (!input) return;

    input.addEventListener('keyup', function () {
      var q = (this.value || '').toLowerCase().trim();
      var hasVisible = false;

      document.querySelectorAll('.plan-item').forEach(function (el) {
        var hay = el.getAttribute('data-name') || '';
        var show = hay.indexOf(q) !== -1;
        el.style.display = show ? '' : 'none';
        if (show) hasVisible = true;
      });

      // Show empty state if no plans match
      if (!hasVisible && q.length > 0) {
        // Optional: Add empty state message
      }
    });
  })();
</script>
@endpush
@endsection
