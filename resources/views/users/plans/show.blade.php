@extends('layouts.user')

@section('content')
<div class="container py-4">

  {{-- Top bar --}}
  <div class="align-items-center justify-content-between mb-3">
    <div>
      <h3 class="mb-1">{{ $plan->name }}</h3>
      <div class="text-muted">
        {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}
        <span class="mx-1">•</span>
        {{ ucfirst($plan->billing_interval) }} billing
      </div>
    </div>
    <a href="{{ route('user.plans.index') }}" class="btn btn-outline-secondary">
      <i class="fa fa-arrow-left me-1"></i> Back
    </a>
  </div>

  <div class="row g-3">
    {{-- Left: Plan details --}}
    <div class="col-lg-7">

      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <div class="text-muted mb-3">{{ $plan->description }}</div>

          @php $features = is_array($plan->features) ? $plan->features : []; @endphp
          @if(count($features))
            <div class="fw-bold mb-2">What you get</div>
            <div class="row">
              @foreach($features as $f)
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-start">
                    <i class="fa fa-check-circle text-success mt-1 me-2"></i>
                    <div>{{ is_array($f) ? json_encode($f) : $f }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="alert alert-light border mb-0">
              <i class="fa fa-info-circle me-1"></i> No features listed yet for this plan.
            </div>
          @endif
        </div>
      </div>

      <div class="alert alert-info border mt-3 mb-0">
        <i class="fa fa-shield me-1"></i>
        Your access is activated immediately after payment confirmation.
      </div>

    </div>

    {{-- Right: Checkout --}}
    <div class="col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h5 class="mb-1">Checkout</h5>
          <div class="text-muted small">Choose your payment method and confirm.</div>
        </div>

        <div class="card-body p-4">

          {{-- Summary --}}
          <div class="p-3 border rounded mb-3">
            <div class="d-flex justify-content-between">
              <div class="text-muted">Plan</div>
              <div class="fw-bold">{{ $plan->name }}</div>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <div class="text-muted">Price</div>
              <div class="fw-bold">{{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}</div>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <div class="text-muted">Billing</div>
              <div class="fw-bold">{{ ucfirst($plan->billing_interval) }}</div>
            </div>
          </div>

          <form method="POST" action="{{ route('user.checkout.start', $plan->slug) }}" id="checkoutForm">
            @csrf

            {{-- Provider cards --}}
            <div class="fw-bold mb-2">Payment method</div>

            @php
              // $banks expected; fallback to empty array
              $banksList = isset($banks) ? $banks : collect();
            @endphp

            @if($banksList && count($banksList))
              <div class="row g-2 mb-3">
                @foreach($banksList as $bank)
                  @php
                    $logoUrl = $bank->logo ? asset('storage/'.$bank->logo) : null;
                    $charges = (float) $bank->charges;
                  @endphp

                  <div class="col-12">
                    <label class="w-100">
                      <input type="radio" name="bank_id" value="{{ $bank->id }}" class="d-none provider-radio" required>
                      <div class="border rounded p-3 provider-card">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center">
                            @if($logoUrl)
                              <img src="{{ $logoUrl }}" alt="logo" style="height:28px; width:auto;" class="me-2">
                            @else
                              <span class="badge bg-light text-dark border me-2">
                                <i class="fa fa-bank"></i>
                              </span>
                            @endif
                            <div>
                              <div class="fw-bold">{{ $bank->payment_owner }}</div>
                              <div class="text-muted small">
                                Charges: {{ number_format($charges, 2) }}%
                                @if(!empty($bank->phone_number))
                                  <span class="mx-1">•</span> Pay to: {{ $bank->phone_number }}
                                @endif
                              </div>
                            </div>
                          </div>

                          <span class="badge bg-success-subtle text-success border">
                            Available
                          </span>
                        </div>
                      </div>
                    </label>
                  </div>
                @endforeach
              </div>

              {{-- keep your provider string too if your backend expects it --}}
              <input type="hidden" name="provider" value="momo">

            @else
              {{-- Fallback to your old select if no banks provided --}}
              <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="provider" class="form-control" required>
                  <option value="momo">Mobile Money (MoMo)</option>
                  <option value="binance">Binance Crypto</option>
                </select>
              </div>
            @endif

            {{-- Phone --}}
            <div class="mb-3">
              <label class="form-label">MoMo Phone (optional)</label>
              <input type="text" name="phone" class="form-control" placeholder="e.g 07xxxxxxxx">
              <div class="text-muted small mt-1">If empty, we use your account phone number.</div>
            </div>

            <button class="btn btn-success w-100">
              <i class="fa fa-lock me-1"></i> Pay & Activate
            </button>

            <div class="text-muted small mt-2">
              By continuing, you agree to the platform payment terms.
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

</div>

@push('styles')
<style>
  .provider-card { transition: 0.15s ease; cursor: pointer; }
  .provider-radio:checked + .provider-card,
  label:has(.provider-radio:checked) .provider-card {
    border-color: rgba(25,135,84,.6) !important;
    box-shadow: 0 0 0 .2rem rgba(25,135,84,.15);
  }
</style>
@endpush

@push('scripts')
<script>
  // Add a visual "selected" effect even on older browsers that don't support :has()
  document.querySelectorAll('.provider-radio').forEach(function(radio){
    radio.addEventListener('change', function(){
      document.querySelectorAll('.provider-card').forEach(c => c.classList.remove('border-success'));
      var card = radio.closest('label').querySelector('.provider-card');
      if(card) card.classList.add('border-success');
    });
  });
</script>
@endpush
@endsection
