@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Open Positions</h4>
      <small class="text-muted">Live open trades (from MT5 or database mirror)</small>
    </div>
  </div>

  {{-- Filters (UI ready for when you connect DB) --}}
  <form method="GET" class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-5">
          <input type="text" name="symbol" value="{{ $symbol }}" class="form-control" placeholder="Symbol (e.g. XAUUSD)">
        </div>
        <div class="col-md-5">
          <select name="type" class="form-control">
            <option value="">All Types</option>
            <option value="BUY"  {{ $type==='BUY' ? 'selected' : '' }}>BUY</option>
            <option value="SELL" {{ $type==='SELL' ? 'selected' : '' }}>SELL</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
          <a href="{{ route('user.trades.open') }}" class="btn btn-secondary w-100">Reset</a>
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
              <th>Ticket</th>
              <th>Symbol</th>
              <th>Type</th>
              <th>Lots</th>
              <th>Open Price</th>
              <th>SL</th>
              <th>TP</th>
              <th>Profit</th>
              <th>Opened</th>
            </tr>
          </thead>
          <tbody>
            @forelse($positions as $i => $p)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->ticket ?? '-' }}</td>
                <td class="fw-bold">{{ $p->symbol ?? '-' }}</td>
                <td>
                  <span class="badge {{ ($p->type ?? '')==='BUY' ? 'bg-success' : 'bg-danger' }}">
                    {{ $p->type ?? '-' }}
                  </span>
                </td>
                <td>{{ $p->lots ?? '-' }}</td>
                <td>{{ $p->open_price ?? '-' }}</td>
                <td>{{ $p->sl ?? '-' }}</td>
                <td>{{ $p->tp ?? '-' }}</td>
                <td>{{ $p->profit ?? '-' }}</td>
                <td>{{ $p->opened_at ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">
                  No open positions yet. (Next step: connect MT5/DB mirror)
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
