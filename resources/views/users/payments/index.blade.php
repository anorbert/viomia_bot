@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Payments</h4>
      <small class="text-muted">Your payment records & invoices</small>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search reference, amount, plan...">
        </div>
        <div class="col-md-4">
          <select name="status" class="form-control">
            <option value="">All Status</option>
            @foreach(['pending','success','failed','cancelled'] as $st)
              <option value="{{ $st }}" {{ $status===$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
          <a href="{{ route('user.payments.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
      </div>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Reference</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Status</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payments as $i => $p)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->created_at ?? '-' }}</td>
                <td class="fw-bold">{{ $p->reference ?? '-' }}</td>
                <td>{{ $p->method ?? '-' }}</td>
                <td>{{ $p->amount ?? '-' }}</td>
                <td>
                  <span class="badge bg-secondary">{{ $p->status ?? '-' }}</span>
                </td>
                <td class="text-end">
                  <a href="#" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-file-text-o"></i> Invoice
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                  No payments found yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
