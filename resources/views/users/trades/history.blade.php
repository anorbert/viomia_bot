@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Trade History</h4>
      <small class="text-muted">Closed trades and account performance</small>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search ticket/comment/symbol...">
        </div>

        <div class="col-md-2">
          <input type="text" name="symbol" value="{{ $symbol }}" class="form-control" placeholder="Symbol">
        </div>

        <div class="col-md-2">
          <select name="type" class="form-control">
            <option value="">All Types</option>
            <option value="BUY"  {{ $type==='BUY' ? 'selected' : '' }}>BUY</option>
            <option value="SELL" {{ $type==='SELL' ? 'selected' : '' }}>SELL</option>
          </select>
        </div>

        <div class="col-md-2">
          <input type="date" name="from" value="{{ $from }}" class="form-control">
        </div>

        <div class="col-md-2">
          <input type="date" name="to" value="{{ $to }}" class="form-control">
        </div>

        <div class="col-md-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
          <a href="{{ route('user.trades.history') }}" class="btn btn-secondary">Reset</a>
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
              <th>Open</th>
              <th>Close</th>
              <th>Profit</th>
              <th>Closed At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($trades as $i => $t)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $t->ticket ?? '-' }}</td>
                <td class="fw-bold">{{ $t->symbol ?? '-' }}</td>
                <td>
                  <span class="badge {{ ($t->type ?? '')==='BUY' ? 'bg-success' : 'bg-danger' }}">
                    {{ $t->type ?? '-' }}
                  </span>
                </td>
                <td>{{ $t->lots ?? '-' }}</td>
                <td>{{ $t->open_price ?? '-' }}</td>
                <td>{{ $t->close_price ?? '-' }}</td>
                <td>{{ $t->profit ?? '-' }}</td>
                <td>{{ $t->closed_at ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                  No trade history yet. (Next step: connect trades table)
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- When you switch to paginate(), add links here --}}
    {{-- <div class="card-footer">{{ $trades->links() }}</div> --}}
  </div>

</div>
@endsection
