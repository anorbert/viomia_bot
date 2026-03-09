@extends('layouts.user')

@section('content')
<div class="container py-4">

  {{-- Top bar --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h3 class="mb-1">
        <i class="fa fa-star text-warning me-2"></i>{{ $plan->name }}
      </h3>
      <div class="text-muted">
        {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}
        <span class="mx-2">•</span>
        <i class="fa fa-calendar me-1"></i>{{ ucfirst($plan->billing_interval) }} Billing
        @if($plan->duration_days)
          <span class="mx-2">•</span>
          <i class="fa fa-clock-o me-1"></i>{{ $plan->duration_days }} days
        @endif
      </div>
    </div>
    <a href="{{ route('user.plans.index') }}" class="btn btn-outline-secondary">
      <i class="fa fa-arrow-left me-1"></i> Back to Plans
    </a>
  </div>

  <div class="row g-4">
    {{-- Left: Plan details --}}
    <div class="col-lg-7">

      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-4">
          <p class="text-muted mb-3">{{ $plan->description }}</p>

          @php $features = is_array($plan->features) ? $plan->features : []; @endphp
          @if(count($features))
            <div class="fw-bold mb-3">
              <i class="fa fa-check-circle text-success me-2"></i>What You Get
            </div>
            <div class="row">
              @foreach($features as $f)
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-start">
                    <i class="fa fa-check text-success me-3 mt-1"></i>
                    <div class="flex-grow-1">
                      {{ is_array($f) ? json_encode($f) : $f }}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="alert alert-light border mb-0">
              <i class="fa fa-info-circle me-1"></i> 
              No features listed yet for this plan.
            </div>
          @endif
        </div>
      </div>

      <div class="alert alert-info border-0 mb-0">
        <i class="fa fa-lightning me-2"></i>
        <strong>Instant Activation:</strong> Your access is activated immediately after payment confirmation.
      </div>

    </div>

    {{-- Right: Checkout --}}
    <div class="col-lg-5">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient bg-primary text-white border-0 p-4 pb-3">
          <h5 class="mb-1">
            <i class="fa fa-credit-card me-2"></i>Complete Your Purchase
          </h5>
          <p class="text-white-50 small mb-0">Secure checkout - Payment processed immediately</p>
        </div>

        <div class="card-body p-4">

          {{-- Price Summary --}}
          <div class="bg-light p-3 rounded mb-4 border-start border-primary" style="border-left-width: 4px !important;">
            <div class="row mb-2">
              <div class="col">Plan:</div>
              <div class="col-auto fw-bold">{{ $plan->name }}</div>
            </div>
            <div class="row mb-2">
              <div class="col">Billing Cycle:</div>
              <div class="col-auto fw-bold">{{ ucfirst($plan->billing_interval) }}</div>
            </div>
            <div class="row border-top pt-2 mt-2">
              <div class="col fw-bold">Total Amount:</div>
              <div class="col-auto fs-5 fw-bold text-primary">
                {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('user.checkout.start', $plan->slug) }}" id="checkoutForm">
            @csrf

            {{-- Provider Selection --}}
            <div class="mb-4">
              <label class="form-label fw-bold mb-3">
                <i class="fa fa-credit-card me-2 text-primary"></i>Select Payment Method
              </label>

              @php
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
                      <label class="w-100 position-relative">
                        <input type="radio" name="bank_id" value="{{ $bank->id }}" class="d-none provider-radio" required>
                        <div class="border-2 rounded-3 p-3 provider-card bg-white h-100" style="cursor: pointer; transition: all 0.2s ease;">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-grow-1">
                              @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="logo" style="height:32px; width:auto;" class="me-3">
                              @else
                                <span class="badge bg-primary me-3">
                                  <i class="fa fa-mobile fa-lg"></i>
                                </span>
                              @endif
                              <div>
                                <div class="fw-bold mb-1">{{ $bank->payment_owner }}</div>
                                <div class="text-muted small">
                                  @if(!empty($bank->phone_number))
                                    Pay to: <strong>{{ $bank->phone_number }}</strong>
                                    @if($charges > 0)
                                      <span class="mx-1">•</span>
                                    @endif
                                  @endif
                                  @if($charges > 0)
                                    Fees: {{ number_format($charges, 2) }}%
                                  @endif
                                </div>
                              </div>
                            </div>

                            <span class="badge bg-success rounded-pill ms-2">
                              <i class="fa fa-check-circle me-1"></i>Available
                            </span>
                          </div>
                        </div>
                      </label>
                    </div>
                  @endforeach
                </div>

                <input type="hidden" name="provider" value="momo">

              @else
                {{-- Fallback --}}
                <div class="row g-2">
                  <div class="col-6">
                    <label class="w-100 position-relative">
                      <input type="radio" name="provider" value="momo" class="d-none provider-radio" checked required>
                      <div class="border-2 rounded-3 p-3 provider-card text-center bg-white" style="cursor: pointer; transition: all 0.2s ease;">
                        <i class="fa fa-mobile fa-2x text-success mb-2 d-block"></i>
                        <div class="fw-bold small">Mobile Money</div>
                        <div class="text-muted smaller">MoMo / MTN</div>
                      </div>
                    </label>
                  </div>
                  <div class="col-6">
                    <label class="w-100 position-relative">
                      <input type="radio" name="provider" value="binance" class="d-none provider-radio" required>
                      <div class="border-2 rounded-3 p-3 provider-card text-center bg-white" style="cursor: pointer; transition: all 0.2s ease;">
                        <i class="fa fa-bitcoin fa-2x text-warning mb-2 d-block"></i>
                        <div class="fw-bold small">Binance Pay</div>
                        <div class="text-muted smaller">Crypto</div>
                      </div>
                    </label>
                  </div>
                </div>
              @endif
            </div>

            {{-- Phone Number (for MoMo) --}}
            <div class="mb-3" id="phoneGroup" style="display: none;">
              <label class="form-label small text-muted">
                <i class="fa fa-phone me-1"></i>Mobile Money Phone (Optional)
              </label>
              <input type="tel" name="phone" class="form-control rounded-2" placeholder="e.g. 0712345678" maxlength="30">
              <small class="text-muted d-block mt-1">
                If empty, we'll use the phone number on your account.
              </small>
            </div>

            {{-- Binance Requirements --}}
            <div class="mb-3" id="binanceGroup" style="display: none;">
              <label class="form-label small text-muted">
                <i class="fa fa-bitcoin me-1"></i>Binance Pay Requirements
              </label>
              <div class="alert alert-info border-0 mb-3">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Instructions:</strong>
                <ul class="mb-0 ps-3 mt-2">
                  <li>You'll be redirected to Binance Pay to complete the transaction</li>
                  <li>Have your Binance account ready</li>
                  <li>Transaction completes within minutes</li>
                </ul>
              </div>
              <label class="form-label small text-muted">
                <i class="fa fa-user-circle me-1"></i>Binance Account Email/UID
              </label>
              <input type="text" name="binance_account" class="form-control rounded-2" placeholder="Your Binance email or UID (optional)" maxlength="100">
              <small class="text-muted d-block mt-1">
                Optional. We'll confirm your payment after you complete the Binance Pay confirmation.
              </small>
            </div>

            {{-- Terms & Conditions --}}
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="agreeTerms" required>
              <label class="form-check-label small" for="agreeTerms">
                I agree to the 
                <a href="#" class="text-decoration-none">payment terms & conditions</a>
              </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-success btn-lg w-100 rounded-2" id="submitBtn">
              <i class="fa fa-lock me-2"></i>
              <span id="btnText">Pay {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}</span>
              <span id="spinnerIcon" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>

            <div class="text-center mt-3">
              <small class="text-muted d-block">
                <i class="fa fa-shield me-1"></i>
                Secure payment processed by trusted providers
              </small>
            </div>
          </form>

        </div>
      </div>

      {{-- Trust Badges --}}
      <div class="row g-2 mt-3">
        <div class="col-6">
          <div class="text-center">
            <small class="text-muted d-block">
              <i class="fa fa-check-circle text-success me-1"></i>SSL Secured
            </small>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center">
            <small class="text-muted d-block">
              <i class="fa fa-redo text-success me-1"></i>Money-Back Guarantee
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@push('styles')
<style>
  .bg-gradient {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
  }

  .provider-card {
    border: 2px solid #dee2e6;
    transition: all 0.2s ease;
  }

  .provider-radio:checked + .provider-card {
    border-color: #28a745;
    background-color: #f8fff9 !important;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
  }

  .provider-card:hover {
    border-color: #0d6efd;
    transform: translateY(-2px);
  }

  .form-control, .form-check-input {
    border-radius: 0.5rem;
    border-width: 1.5px;
    border-color: #dee2e6;
  }

  .form-control:focus, .form-check-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
  }

  .btn-success:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }

  #checkoutForm {
    animation: slideUp 0.3s ease;
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endpush

@push('scripts')
<script>
  const form = document.getElementById('checkoutForm');
  const phoneGroup = document.getElementById('phoneGroup');
  const binanceGroup = document.getElementById('binanceGroup');
  const providerRadios = document.querySelectorAll('.provider-radio');
  const submitBtn = document.getElementById('submitBtn');
  const spinnerIcon = document.getElementById('spinnerIcon');
  const btnText = document.getElementById('btnText');

  // Update form fields visibility based on provider selection
  function updateProviderUI() {
    const selectedProvider = document.querySelector('.provider-radio:checked').value;
    
    // Show/hide form fields based on selected provider
    if (phoneGroup && binanceGroup) {
      if (selectedProvider === 'momo') {
        phoneGroup.style.display = 'block';
        binanceGroup.style.display = 'none';
        phoneGroup.querySelector('input[name="phone"]').removeAttribute('required');
      } else if (selectedProvider === 'binance') {
        phoneGroup.style.display = 'none';
        binanceGroup.style.display = 'block';
        binanceGroup.querySelector('input[name="binance_account"]').removeAttribute('required');
      } else {
        phoneGroup.style.display = 'none';
        binanceGroup.style.display = 'none';
      }
    }

    // Visual feedback for provider cards
    document.querySelectorAll('.provider-card').forEach(card => {
      const radio = card.closest('label').querySelector('.provider-radio');
      if (radio && radio.checked) {
        card.style.borderColor = '#28a745';
        card.style.backgroundColor = '#f8fff9';
        card.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
      } else {
        card.style.borderColor = '#dee2e6';
        card.style.backgroundColor = 'white';
        card.style.boxShadow = 'none';
      }
    });
  }

  // Initialize on page load
  updateProviderUI();

  // Listen to provider changes
  providerRadios.forEach(radio => {
    radio.addEventListener('change', updateProviderUI);
  });

  // Form submission
  form.addEventListener('submit', function(e) {
    const selectedProvider = document.querySelector('.provider-radio:checked').value;
    
    // Validate based on provider
    if (selectedProvider === 'momo') {
      // Optional phone validation can be added here
    } else if (selectedProvider === 'binance') {
      // Optional Binance account validation can be added here
    }
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    spinnerIcon.classList.remove('d-none');
    btnText.textContent = 'Processing...';
  });

  // Enhanced visual feedback for provider card interactions
  document.querySelectorAll('.provider-card').forEach(card => {
    card.addEventListener('click', function() {
      const radio = this.closest('label').querySelector('.provider-radio');
      if (radio) {
        radio.checked = true;
        radio.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  });
</script>
@endpush
@endsection
