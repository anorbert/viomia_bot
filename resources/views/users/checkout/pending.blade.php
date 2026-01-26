@extends('layouts.user')

@section('content')
<div class="container py-4">
  <h4 class="mb-1">Payment Pending</h4>
  <div class="text-muted mb-3">
    Reference: <span class="fw-bold">{{ $txn->reference }}</span> |
    Amount: <span class="fw-bold">{{ $txn->currency }} {{ number_format((float)$txn->amount, 2) }}</span>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        Waiting for payment confirmation... This page will update automatically.
      </div>

      <div class="d-flex gap-2">
        @if($txn->provider === 'binance' && $txn->checkout_url)
          <a href="{{ $txn->checkout_url }}" class="btn btn-primary">
            <i class="fa fa-external-link"></i> Continue to Binance
          </a>
        @endif

        <a href="{{ route('user.subscriptions.index') }}" class="btn btn-outline-secondary">
          Go to My Subscription
        </a>
      </div>

      <hr>

      <div>
        <div class="fw-bold">Current status:</div>
        <div id="statusText" class="badge bg-warning text-dark">{{ ucfirst($txn->status) }}</div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const ref = @json($txn->reference);
  const statusUrl = @json(route('user.checkout.status', $txn->reference));

  function poll(){
    fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(r => r.json())
      .then(data => {
        const s = (data.status || 'pending').toLowerCase();
        const el = document.getElementById('statusText');

        el.textContent = s.toUpperCase();

        el.className = 'badge ' + (
          s === 'success' ? 'bg-success' :
          (s === 'failed' || s === 'cancelled') ? 'bg-danger' :
          'bg-warning text-dark'
        );

        if (s === 'success') {
          window.location.href = @json(route('user.subscriptions.index'));
          return;
        }
        if (s === 'failed' || s === 'cancelled') {
          // stay here; user can retry from plans
          return;
        }
        setTimeout(poll, 4000);
      })
      .catch(() => setTimeout(poll, 5000));
  }

  setTimeout(poll, 2000);
})();
</script>
@endsection
