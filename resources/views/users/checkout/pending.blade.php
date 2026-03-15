@extends('layouts.user')

@section('content')
<div class="container py-4">
  <!-- Header -->
  <div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h4 class="mb-0">
        <i class="fa fa-credit-card text-primary me-2"></i>
        Payment Processing
      </h4>
      <span id="loadingIndicator" class="badge bg-primary d-none">
        <i class="fa fa-spinner fa-spin me-1"></i> Checking...
      </span>
    </div>
    <p class="text-muted mb-0">Reference: <code>{{ $txn->reference }}</code></p>
  </div>

  <div class="row g-4">
    <!-- Order Details Card -->
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
          <h6 class="mb-0"><i class="fa fa-receipt me-2"></i>Order Summary</h6>
        </div>
        <div class="card-body">
          <!-- Status Alert -->
          <div id="statusAlert" class="alert alert-info mb-4" role="alert">
            <div class="d-flex align-items-center gap-3">
              <div>
                <i id="statusIcon" class="fa fa-hourglass-half text-warning fa-lg"></i>
              </div>
              <div>
                <div class="fw-bold" id="statusMessage">Waiting for payment confirmation...</div>
                <small class="text-muted" id="statusSubtext">This page will auto-update every 4 seconds</small>
              </div>
            </div>
          </div>

          <!-- Order Details -->
          <div class="mb-4">
            <div class="row mb-3">
              <div class="col-5 text-muted">Plan Name:</div>
              <div class="col-7">
                <span class="fw-bold">{{ $txn->plan->name ?? 'Unknown Plan' }}</span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-5 text-muted">Billing Period:</div>
              <div class="col-7">
                <span class="fw-bold">{{ ucfirst($txn->plan->billing_interval ?? 'monthly') }}</span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-5 text-muted">Duration:</div>
              <div class="col-7">
                <span class="fw-bold">
                  @if($txn->plan->duration_days)
                    {{ $txn->plan->duration_days }} days
                  @elseif($txn->plan->billing_interval === 'yearly')
                    12 months
                  @elseif($txn->plan->billing_interval === 'weekly')
                    7 days
                  @else
                    30 days
                  @endif
                </span>
              </div>
            </div>

            <div class="row mb-3 pb-3 border-bottom">
              <div class="col-5 text-muted">Payment Method:</div>
              <div class="col-7">
                <span class="badge bg-info">
                  @if($txn->provider === 'binance')
                    <i class="fa fa-bitcoin me-1"></i>Binance Pay
                  @elseif($txn->provider === 'momo')
                    <i class="fa fa-mobile me-1"></i>Mobile Money
                  @else
                    {{ ucfirst($txn->provider) }}
                  @endif
                </span>
              </div>
            </div>
          </div>

          <!-- Price Breakdown -->
          <div class="bg-light p-3 rounded mb-4">
            <div class="row mb-3">
              <div class="col-6">Subtotal:</div>
              <div class="col-6 text-end">
                {{ $txn->currency }} {{ number_format((float)$txn->plan->price, 2) }}
              </div>
            </div>
            @if($txn->plan->tax_rate)
            <div class="row mb-3">
              <div class="col-6">Tax ({{ $txn->plan->tax_rate }}%):</div>
              <div class="col-6 text-end">
                {{ $txn->currency }} {{ number_format((float)$txn->plan->price * $txn->plan->tax_rate / 100, 2) }}
              </div>
            </div>
            @endif
            <div class="row border-top pt-3 fw-bold fs-6">
              <div class="col-6">Total Amount:</div>
              <div class="col-6 text-end">
                {{ $txn->currency }} <span id="totalAmount">{{ number_format((float)$txn->amount, 2) }}</span>
              </div>
            </div>
          </div>

          <!-- Status Badge -->
          <div class="text-center mb-4">
            <div class="text-muted small mb-2">Transaction Status:</div>
            <div id="statusBadge" class="badge bg-warning text-dark px-3 py-2 fs-6">
              <i class="fa fa-hourglass-half me-2"></i>{{ ucfirst($txn->status) }}
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex gap-2 flex-wrap">
            @if($txn->provider === 'binance' && $txn->checkout_url)
              <a href="{{ $txn->checkout_url }}" class="btn btn-primary flex-grow-1">
                <i class="fa fa-external-link me-2"></i> Continue to Binance
              </a>
            @elseif($txn->provider === 'momo')
              <button type="button" class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#momoModal">
                <i class="fa fa-mobile me-2"></i> Complete MoMo Payment
              </button>
            @endif

            <button type="button" onclick="location.reload()" class="btn btn-outline-secondary">
              <i class="fa fa-refresh me-2"></i> Refresh
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-5">
      <!-- Help Card -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light border-bottom">
          <h6 class="mb-0"><i class="fa fa-info-circle me-2 text-info"></i>Need Help?</h6>
        </div>
        <div class="card-body small">
          <div class="mb-3">
            <strong>How long does it take?</strong>
            <p class="text-muted mb-0">Most payments are confirmed within 2-5 minutes. This page will automatically update.</p>
          </div>

          <div class="mb-3">
            <strong>Payment Failed?</strong>
            <p class="text-muted mb-0">Return to the plans page and try again with a different payment method.</p>
          </div>

          <div>
            <strong>Security Notice</strong>
            <p class="text-muted mb-0">Never share your payment reference or sensitive information in emails or messages.</p>
          </div>
        </div>
      </div>

      <!-- Back Button Card -->
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <a href="{{ route('user.subscriptions.index') }}" class="btn btn-outline-secondary w-100">
            <i class="fa fa-arrow-left me-2"></i> Go to My Subscriptions
          </a>
          <hr class="my-3">
          <a href="{{ route('user.plans.index') }}" class="btn btn-outline-secondary w-100">
            <i class="fa fa-list me-2"></i> View Other Plans
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MoMo Modal (if applicable) -->
@if($txn->provider === 'momo')
<div class="modal fade" id="momoModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-mobile me-2"></i>Mobile Money Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>A payment request has been sent to:</p>
        <div class="alert alert-info mb-3">
          <strong id="momoPhone">{{ $txn->payload['momo_phone'] ?? 'Your phone number' }}</strong>
        </div>
        <p>Please enter your Mobile Money PIN on your phone to complete the payment.</p>
        <p class="text-muted small">Amount: <strong>{{ $txn->currency }} {{ number_format((float)$txn->amount, 2) }}</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
        <button type="button" class="btn btn-primary" onclick="this.disabled=true; window.location.reload();">
          <i class="fa fa-check me-2"></i> Payment Complete
        </button>
      </div>
    </div>
  </div>
</div>
@endif

<script>
(function() {
  const ref = @json($txn->reference);
  const statusUrl = @json(route('user.checkout.status', $txn->reference));
  const subscriptionsUrl = @json(route('user.subscriptions.index'));
  
  let pollCount = 0;
  const maxPolls = 180; // 12 minutes max polling
  let pollInterval = 4000; // Start at 4 seconds

  function updateStatusDisplay(status, paidAt = null) {
    const badge = document.getElementById('statusBadge');
    const alert = document.getElementById('statusAlert');
    const icon = document.getElementById('statusIcon');
    const message = document.getElementById('statusMessage');
    const subtext = document.getElementById('statusSubtext');

    const statusLower = (status || 'pending').toLowerCase();

    // Update badge
    badge.textContent = statusLower.toUpperCase();
    
    if (statusLower === 'success') {
      badge.className = 'badge bg-success px-3 py-2 fs-6';
      badge.innerHTML = '<i class="fa fa-check-circle me-2"></i>' + statusLower.toUpperCase();
      
      alert.className = 'alert alert-success mb-4';
      icon.className = 'fa fa-check-circle text-success fa-lg';
      message.textContent = 'Payment Confirmed!';
      subtext.textContent = paidAt ? 'Paid at: ' + paidAt : 'Your subscription is now active';
      
      // Auto-redirect after 3 seconds
      setTimeout(() => {
        window.location.href = subscriptionsUrl;
      }, 3000);
    } 
    else if (statusLower === 'failed' || statusLower === 'cancelled') {
      badge.className = 'badge bg-danger px-3 py-2 fs-6';
      badge.innerHTML = '<i class="fa fa-times-circle me-2"></i>' + statusLower.toUpperCase();
      
      alert.className = 'alert alert-danger mb-4';
      icon.className = 'fa fa-times-circle text-danger fa-lg';
      message.textContent = 'Payment ' + statusLower.charAt(0).toUpperCase() + statusLower.slice(1);
      subtext.textContent = 'Please try again or contact support';
    } 
    else {
      badge.className = 'badge bg-warning text-dark px-3 py-2 fs-6';
      badge.innerHTML = '<i class="fa fa-hourglass-half me-2"></i>' + statusLower.toUpperCase();
      
      alert.className = 'alert alert-info mb-4';
      icon.className = 'fa fa-hourglass-half text-warning fa-lg';
      message.textContent = 'Waiting for payment confirmation...';
      subtext.textContent = 'This page will auto-update every ' + (pollInterval / 1000) + ' seconds';
    }
  }

  function poll() {
    const loader = document.getElementById('loadingIndicator');
    loader?.classList.remove('d-none');

    fetch(statusUrl, { 
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(r => {
        if (!r.ok) throw new Error('Status check failed');
        return r.json();
      })
      .then(data => {
        loader?.classList.add('d-none');
        const status = (data.status || 'pending').toLowerCase();
        
        updateStatusDisplay(status, data.paid_at);

        // Stop polling if successful
        if (status === 'success') {
          return;
        }

        // Stop polling if failed/cancelled (don't keep retrying)
        if (status === 'failed' || status === 'cancelled') {
          return;
        }

        pollCount++;
        
        // Gradually increase polling interval
        if (pollCount > 30) {
          pollInterval = 8000;
        }
        if (pollCount > 60) {
          pollInterval = 15000;
        }

        // Stop after max polls
        if (pollCount < maxPolls) {
          setTimeout(poll, pollInterval);
        } else {
          loader?.classList.add('d-none');
          updateStatusDisplay('timeout');
        }
      })
      .catch(err => {
        loader?.classList.add('d-none');
        console.error('Poll error:', err);
        
        if (pollCount < maxPolls) {
          setTimeout(poll, Math.min(pollInterval * 1.5, 30000));
        }
      });
  }

  // Start polling after a brief delay
  setTimeout(poll, 1500);
})();
</script>
@endsection
