@extends('layouts.user')

@section('content')

<style>
  .payment-pending * { box-sizing: border-box; }
  .payment-pending {
    font-family: 'Inter', sans-serif;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
    color: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .pending-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 40px;
    max-width: 500px;
    width: 100%;
    text-align: center;
  }

  .pending-icon {
    font-size: 60px;
    margin-bottom: 20px;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .pending-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
  }

  .pending-description {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 24px;
    line-height: 1.6;
  }

  .payment-details {
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
    text-align: left;
  }

  .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }

  .detail-value {
    font-size: 14px;
    color: #0f172a;
    font-weight: 600;
  }

  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    background: #fef9c3;
    color: #854d0e;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 16px;
  }

  .status-badge.success {
    background: #dcfce7;
    color: #15803d;
  }

  .status-badge.failed {
    background: #fee2e2;
    color: #b91c1c;
  }

  .info-box {
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
  }

  .info-box i {
    flex-shrink: 0;
    margin-top: 1px;
  }

  .btn-group {
    display: flex;
    gap: 8px;
    justify-content: center;
  }

  .btn-primary, .btn-secondary {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
  }

  .btn-primary {
    background: #6366f1;
    color: #fff;
  }

  .btn-primary:hover {
    background: #4f46e5;
  }

  .btn-secondary {
    background: #fff;
    color: #475569;
    border: 1px solid #e2e8f0;
  }

  .btn-secondary:hover {
    background: #f8fafc;
  }

  .countdown {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 12px;
  }
</style>

<div class="payment-pending">
  <div class="pending-container">

    {{-- Status Badge --}}
    <div id="statusBadge" class="status-badge">
      ⏳ Waiting for Payment
    </div>

    {{-- Icon --}}
    <div class="pending-icon" id="statusIcon">⏳</div>

    {{-- Title --}}
    <h1 class="pending-title" id="statusTitle">Payment Processing</h1>

    {{-- Description --}}
    <p class="pending-description" id="statusDescription">
      We're waiting for your payment confirmation on your MOMO app. Please check your phone for the payment request and enter your PIN to confirm.
    </p>

    {{-- Payment Details --}}
    <div class="payment-details">
      <div class="detail-row">
        <span class="detail-label">Amount</span>
        <span class="detail-value">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Reference</span>
        <span class="detail-value">#{{ $payment->reference }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Plan</span>
        <span class="detail-value">{{ $payment->plan->name ?? 'Standard' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value" id="detailStatus">Pending</span>
      </div>
    </div>

    {{-- Info Box --}}
    <div class="info-box">
      <i class="fa fa-info-circle"></i>
      <span>Please complete payment on your phone. This page will automatically update when payment is confirmed.</span>
    </div>

    {{-- Actions --}}
    <div class="btn-group">
      <button type="button" class="btn-primary" id="backBtn" onclick="location.href='{{ route('user.subscriptions.index') }}';">
        Back to Subscriptions
      </button>
    </div>

    <div class="countdown">Checking payment status every 5 seconds...</div>

  </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
  'use strict';

  const reference = '{{ $payment->reference }}';
  const checkUrl = '{{ route("user.subscriptions.payment-status", $payment->reference) }}';
  const statusBadge = document.getElementById('statusBadge');
  const statusIcon = document.getElementById('statusIcon');
  const statusTitle = document.getElementById('statusTitle');
  const statusDescription = document.getElementById('statusDescription');
  const detailStatus = document.getElementById('detailStatus');
  const backBtn = document.getElementById('backBtn');

  let checkCount = 0;
  const maxChecks = 60; // 5 minutes at 5-second intervals

  function checkPaymentStatus() {
    fetch(checkUrl, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'paid' || data.status === 'success') {
        // Payment successful
        statusBadge.textContent = '✓ Payment Confirmed';
        statusBadge.classList.add('success');
        statusIcon.textContent = '✅';
        statusIcon.style.animation = 'none';
        statusTitle.textContent = 'Payment Successful!';
        statusDescription.textContent = 'Your subscription is now active. You can access all features immediately.';
        detailStatus.textContent = 'Paid';
        detailStatus.style.color = '#10b981';

        // Show success message
        setTimeout(() => {
          alert('Payment confirmed! Your subscription is now active.');
          location.href = '{{ route("user.subscriptions.index") }}';
        }, 2000);
      } else if (data.status === 'failed') {
        // Payment failed
        statusBadge.textContent = '✗ Payment Failed';
        statusBadge.classList.add('failed');
        statusIcon.textContent = '❌';
        statusIcon.style.animation = 'none';
        statusTitle.textContent = 'Payment Failed';
        statusDescription.textContent = 'Your payment could not be processed. Please try again or contact support.';
        detailStatus.textContent = 'Failed';
        detailStatus.style.color = '#b91c1c';
      } else {
        // Still pending
        checkCount++;
        if (checkCount < maxChecks) {
          setTimeout(checkPaymentStatus, 5000); // Check again in 5 seconds
        } else {
          // Timeout after 5 minutes
          statusBadge.textContent = '⏱ Expired';
          statusBadge.classList.add('failed');
          statusIcon.textContent = '⏱';
          statusIcon.style.animation = 'none';
          statusTitle.textContent = 'Payment Timeout';
          statusDescription.textContent = 'Payment confirmation took too long. Please try again.';
        }
      }
    })
    .catch(error => {
      console.error('Error checking payment status:', error);
      checkCount++;
      if (checkCount < maxChecks) {
        setTimeout(checkPaymentStatus, 5000); // Retry in 5 seconds
      }
    });
  }

  // Start checking immediately
  checkPaymentStatus();
})();
</script>
@endpush
