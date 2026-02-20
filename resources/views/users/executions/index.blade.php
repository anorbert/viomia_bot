@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">EA Executions</h4>
      <small class="text-muted">Execution logs for signals per EA account</small>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-5">
          <input type="text" name="account" value="{{ $account }}" class="form-control" placeholder="Search by account id...">
        </div>
        <div class="col-md-5">
          <select name="status" class="form-control">
            <option value="">All Status</option>
            @foreach(['received','executed','failed'] as $st)
              <option value="{{ $st }}" {{ $status===$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
          <a href="{{ route('user.executions.index') }}" class="btn btn-secondary w-100">Reset</a>
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
              <th>Created</th>
              <th>Account</th>
              <th>Status</th>
              <th>Signal</th>
              <th>Symbol</th>
              <th>Type</th>
            </tr>
          </thead>
          <tbody>
            @forelse($executions as $i => $ex)
              <tr>
                <td>{{ $executions->firstItem() + $i }}</td>
                <td>{{ $ex->created_at?->format('Y-m-d H:i') }}</td>
                <td class="fw-bold">{{ $ex->account_id }}</td>
                <td>
                  @php
                    $badge = match($ex->status){
                      'received' => 'bg-warning text-dark',
                      'executed' => 'bg-success',
                      'failed' => 'bg-danger',
                      default => 'bg-info'
                    };
                  @endphp
                  <span class="badge {{ $badge }}">{{ ucfirst($ex->status) }}</span>
                </td>

                <td>#{{ $ex->whatsapp_signal_id }}</td>
                <td>{{ $ex->signal?->symbol ?? '-' }}</td>
                <td>
                  @if($ex->signal)
                    <span class="badge {{ $ex->signal->type==='BUY' ? 'bg-success' : 'bg-danger' }}">
                      {{ $ex->signal->type }}
                    </span>
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">No executions found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{ $executions->links() }}
    </div>
  </div>

</div>
@endsection
