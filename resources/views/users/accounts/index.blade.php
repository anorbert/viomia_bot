@extends('layouts.user')
@section('content')

<div class="container-fluid" style="padding-top:0;">

  <div class="row mb-3">
    <div class="col-md-8 col-12">
      <h3 class="mb-0">My Accounts</h3>
      <small class="text-muted">Connect and manage your trading accounts used by the bot</small>
    </div>
    <div class="col-md-4 col-12 text-md-right mt-2 mt-md-0">
      <button class="btn btn-success" data-toggle="modal" data-target="#addAccountModal">
        <i class="fa fa-plus"></i> Connect Account
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul style="margin-bottom:0;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="x_panel">
    <div class="x_title">
      <h2>Connected Accounts</h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">

      <div class="row mb-2">
        <div class="col-md-6">
          <input type="text" id="tableSearch" class="form-control" placeholder="Search login, server, platform...">
        </div>
        <div class="col-md-6 text-md-right mt-2 mt-md-0">
          <span class="text-muted">Total: <strong>{{ $accounts->count() }}</strong></span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="accountsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Login</th>
              <th>Platform</th>
              <th>Server</th>
              <th>Type</th>
              <th>Meta</th>
              <th>Health</th>
              <th>Status</th>
              <th width="150">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($accounts as $i => $acc)
              <tr>
                <td>{{ $i+1 }}</td>
                <td><strong>{{ $acc->login }}</strong></td>
                <td>{{ $acc->platform }}</td>
                <td>{{ $acc->server }}</td>
                <td>{{ $acc->account_type ?? '—' }}</td>
                <td>
                  @php
                    $meta = $acc->meta ?? [];
                    $currency = $meta['currency'] ?? null;
                    $lev = $meta['leverage'] ?? null;
                  @endphp
                  <small class="text-muted">
                    {{ $currency ? "Cur: $currency" : '' }}
                    {{ ($currency && $lev) ? ' • ' : '' }}
                    {{ $lev ? "Lev: $lev" : '' }}
                    {{ (!$currency && !$lev) ? '—' : '' }}
                  </small>
                </td>
                <td>
                  <span class="label label-{{ $acc->connected ? 'success' : 'danger' }}">
                    {{ $acc->connected ? 'CONNECTED' : 'OFFLINE' }}
                  </span>
                </td>
                <td>
                  <span class="label label-{{ $acc->active ? 'success' : 'default' }}" id="status-badge-{{ $acc->id }}">
                    {{ $acc->active ? 'ACTIVE' : 'INACTIVE' }}
                  </span>
                </td>
                <td class="text-right">
                  <button
                    class="btn btn-xs btn-info editBtn"
                    data-id="{{ $acc->id }}"
                    data-login="{{ $acc->login }}"
                    data-platform="{{ $acc->platform }}"
                    data-server="{{ $acc->server }}"
                    data-type="{{ $acc->account_type }}"
                    data-currency="{{ $meta['currency'] ?? '' }}"
                    data-leverage="{{ $meta['leverage'] ?? '' }}"
                    data-toggle="modal"
                    data-target="#editAccountModal"
                  >
                    <i class="fa fa-edit"></i>
                  </button>

                  <button class="btn btn-xs btn-warning toggleBtn" data-id="{{ $acc->id }}">
                    <i class="fa fa-power-off"></i>
                  </button>

                  <form method="POST" action="{{ route('user.accounts.destroy', $acc->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-xs btn-danger" onclick="return confirm('Remove this account?')">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted">No accounts connected yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('user.accounts.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-plus"></i> Connect Account</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Platform</label>
            <select class="form-control" name="platform" required>
              <option value="MT5">MT5</option>
              <option value="MT4">MT4</option>
              <option value="cTrader">cTrader</option>
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label>Account Type</label>
            <select class="form-control" name="account_type">
              <option value="">—</option>
              <option value="real">Real</option>
              <option value="demo">Demo</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Server</label>
          <input class="form-control" name="server" required value="{{ old('server') }}">
        </div>

        <div class="form-group">
          <label>Login</label>
          <input class="form-control" name="login" required value="{{ old('login') }}">
        </div>

        <div class="form-group">
          <label>Password</label>
          <input class="form-control" name="password" type="password" required>
          <small class="text-muted">Stored encrypted. Never shown in UI.</small>
        </div>

        <div class="row">
          <div class="col-md-6 form-group">
            <label>Currency (optional)</label>
            <input class="form-control" name="meta[currency]" placeholder="USD">
          </div>
          <div class="col-md-6 form-group">
            <label>Leverage (optional)</label>
            <input class="form-control" name="meta[leverage]" placeholder="1:500">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Connect</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" id="editForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Account</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Platform</label>
            <select class="form-control" name="platform" id="editPlatform" required>
              <option value="MT5">MT5</option>
              <option value="MT4">MT4</option>
              <option value="cTrader">cTrader</option>
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label>Account Type</label>
            <select class="form-control" name="account_type" id="editType">
              <option value="">—</option>
              <option value="Real">Real</option>
              <option value="Demo">Demo</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Server</label>
          <input class="form-control" name="server" id="editServer" required>
        </div>

        <div class="form-group">
          <label>Login</label>
          <input class="form-control" id="editLogin" disabled>
          <small class="text-muted">Login cannot be changed. Remove and add again if needed.</small>
        </div>

        <div class="form-group">
          <label>New Password (optional)</label>
          <input class="form-control" name="password" type="password">
        </div>

        <div class="row">
          <div class="col-md-6 form-group">
            <label>Currency</label>
            <input class="form-control" name="meta[currency]" id="editCurrency">
          </div>
          <div class="col-md-6 form-group">
            <label>Leverage</label>
            <input class="form-control" name="meta[leverage]" id="editLeverage">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){

  // Search
  const search = document.getElementById('tableSearch');
  const table  = document.getElementById('accountsTable');
  if(search && table){
    search.addEventListener('keyup', function(){
      const q = this.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

  // Edit modal fill
  document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function(){
      const id = this.dataset.id;

      document.getElementById('editPlatform').value = this.dataset.platform || 'MT5';
      document.getElementById('editServer').value   = this.dataset.server || '';
      document.getElementById('editLogin').value    = this.dataset.login || '';
      document.getElementById('editType').value     = this.dataset.type || '';
      document.getElementById('editCurrency').value = this.dataset.currency || '';
      document.getElementById('editLeverage').value = this.dataset.leverage || '';

      document.getElementById('editForm').action = "{{ url('user/accounts') }}/" + id;
    });
  });

  // Toggle active (AJAX)
  document.querySelectorAll('.toggleBtn').forEach(btn => {
    btn.addEventListener('click', async function(){
      const id = this.dataset.id;
      try{
        const res = await fetch("{{ url('user/accounts') }}/" + id + "/toggle", {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
          }
        });
        if(!res.ok) return;

        const data = await res.json();
        const badge = document.getElementById('status-badge-' + id);

        if(badge){
          badge.classList.remove('label-success','label-default');
          badge.classList.add(data.active ? 'label-success' : 'label-default');
          badge.innerText = data.active ? 'ACTIVE' : 'INACTIVE';
        }
      }catch(e){}
    });
  });

})();
</script>
@endpush
