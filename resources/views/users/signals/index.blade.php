@extends('layouts.user')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">All Signals</h4>
      <small class="text-muted">WhatsApp signals received by the system</small>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search sender, symbol, raw text...">
        </div>

        <div class="col-md-3">
          <select name="symbol" class="form-control">
            <option value="">All Symbols</option>
            @foreach($symbols as $s)
              <option value="{{ $s }}" {{ $symbol===$s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <select name="status" class="form-control">
            <option value="">All Status</option>
            @foreach(['pending','executed','expired','failed'] as $st)
              <option value="{{ $st }}" {{ $status===$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
          <button class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
          <a href="{{ route('user.signals.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
      </div>
    </div>
  </form>

  {{-- Table --}}
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Received</th>
              <th>Symbol</th>
              <th>Type</th>
              <th>Entry</th>
              <th>SL</th>
              <th>TP</th>
              <th>Sender</th>
              <th>Status</th>
              <th class="text-end">Executions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($signals as $i => $sig)
              <tr>
                <td>{{ $signals->firstItem() + $i }}</td>
                <td>
                  <div>{{ optional($sig->received_at)->format('Y-m-d H:i') }}</div>
                  <small class="text-muted">{{ $sig->group_id }}</small>
                </td>
                <td class="fw-bold">{{ $sig->symbol }}</td>
                <td>
                  <span class="badge {{ $sig->type==='BUY' ? 'bg-success' : 'bg-danger' }}">
                    {{ $sig->type }}
                  </span>
                </td>
                <td>{{ $sig->entry }}</td>
                <td>{{ $sig->stop_loss }}</td>
                <td>
                  @php $tps = is_array($sig->take_profit) ? $sig->take_profit : []; @endphp
                  @if(count($tps))
                    <small>{{ implode(', ', $tps) }}</small>
                  @else
                    <small class="text-muted">-</small>
                  @endif
                </td>
                <td>
                  <div>{{ $sig->sender }}</div>
                </td>
                <td>
                  @php
                    $badge = match($sig->status){
                      'pending' => 'bg-warning text-dark',
                      'executed' => 'bg-success',
                      'expired' => 'bg-secondary',
                      'failed' => 'bg-danger',
                      default => 'bg-info'
                    };
                  @endphp
                  <span class="badge {{ $badge }}">{{ ucfirst($sig->status) }}</span>
                </td>
                <td class="text-end">
                  <span class="badge bg-dark">{{ $sig->executions()->count() }}</span>
                </td>
              </tr>

              @if($sig->raw_text)
              <tr class="table-light">
                <td></td>
                <td colspan="9">
                  <small class="text-muted">Raw:</small>
                  <div class="small">{{ $sig->raw_text }}</div>
                </td>
              </tr>
              @endif

            @empty
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">No signals found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{ $signals->links() }}
    </div>
  </div>

</div>
@endsection
