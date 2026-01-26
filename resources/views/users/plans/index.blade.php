@extends('layouts.user')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="row align-items-center mb-3">
    <div class="col-md-8">
      <h3 class="mb-1">Subscription Plans</h3>
      <div class="text-muted">Choose a plan to activate your trading access.</div>
    </div>
    <div class="col-md-4 mt-3 mt-md-0">
      <div class="input-group">
        <span class="input-group-text"><i class="fa fa-search"></i></span>
        <input id="planSearch" type="text" class="form-control" placeholder="Search plans...">
      </div>
    </div>
  </div>

  {{-- Plans --}}
  <div class="row" id="plansGrid">
    @forelse($plans as $plan)
      @php
        // basic "highlight" idea (optional): highlight most expensive as "Best value"
        $price = (float) $plan->price;
      @endphp

      <div class="col-lg-4 col-md-6 mb-3 plan-item"
           data-name="{{ strtolower($plan->name.' '.$plan->description) }}">

        <div class="card shadow-sm h-100 border-0">
          <div class="card-body p-4">

            {{-- Title row --}}
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <h5 class="mb-1">{{ $plan->name }}</h5>
                <div class="text-muted small">
                  {{ \Illuminate\Support\Str::limit($plan->description, 90) }}
                </div>
              </div>

              {{-- Interval badge --}}
              <span class="badge bg-light text-dark border">
                {{ strtoupper($plan->billing_interval) }}
              </span>
            </div>

            {{-- Price --}}
            <div class="my-3">
              <div class="d-flex align-items-end">
                <div class="display-6 fw-bold lh-1">
                  {{ $plan->currency }} {{ number_format($price, 2) }}
                </div>
                <div class="text-muted ms-2 mb-1">/ {{ $plan->billing_interval }}</div>
              </div>
              <div class="small text-muted mt-1">
                Instant activation after payment confirmation.
              </div>
            </div>

            {{-- Features preview (optional) --}}
            @php $features = is_array($plan->features) ? $plan->features : []; @endphp
            @if(count($features))
              <ul class="list-unstyled small mb-3">
                @foreach(array_slice($features, 0, 4) as $f)
                  <li class="mb-1">
                    <i class="fa fa-check text-success me-2"></i>
                    {{ is_array($f) ? json_encode($f) : $f }}
                  </li>
                @endforeach
                @if(count($features) > 4)
                  <li class="text-muted">+ {{ count($features) - 4 }} more</li>
                @endif
              </ul>
            @else
              <div class="text-muted small mb-3">
                <i class="fa fa-info-circle me-1"></i> Plan features will appear here.
              </div>
            @endif

            <a href="{{ route('user.plans.show', $plan->slug) }}" class="btn btn-primary w-100">
              View & Subscribe <i class="fa fa-arrow-right ms-1"></i>
            </a>

          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-warning mb-0">
          No subscription plans available right now.
        </div>
      </div>
    @endforelse
  </div>

</div>

{{-- Simple search filter (no extra libs) --}}
@push('scripts')
<script>
  (function () {
    var input = document.getElementById('planSearch');
    if(!input) return;

    input.addEventListener('keyup', function () {
      var q = (this.value || '').toLowerCase().trim();
      document.querySelectorAll('.plan-item').forEach(function (el) {
        var hay = el.getAttribute('data-name') || '';
        el.style.display = hay.indexOf(q) !== -1 ? '' : 'none';
      });
    });
  })();
</script>
@endpush
@endsection
