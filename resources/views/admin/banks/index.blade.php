@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">

      <div class="x_title">
        <h2>Payment Hook Registration <small>(Banks for Donation)</small></h2>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">

          {{-- FORM --}}
          <div class="col-md-5 col-sm-12">
            <div class="x_panel">
              <div class="x_title"><h2>Register Bank</h2><div class="clearfix"></div></div>

              <div class="x_content">
                <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Bank Name">
                  </div>

                  <div class="form-group">
                    <label>Logo (optional)</label>
                    <input type="file" class="form-control" name="logo" accept="image/*">
                  </div>

                  <div class="form-group">
                    <label>Support Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="078xxxxxxx">
                    <small class="text-muted">DB uses integer: leading 0 may be removed.</small>
                  </div>

                  <div class="form-group">
                    <label>Confirm Admin Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Your login password">
                  </div>

                  <div class="form-group text-right">
                    <button class="btn btn-success"><i class="fa fa-check"></i> Register</button>
                  </div>

                </form>
              </div>
            </div>
          </div>

          {{-- TABLE --}}
          <div class="col-md-7 col-sm-12">
            <div class="x_panel">
              <div class="x_title"><h2>Registered Banks</h2><div class="clearfix"></div></div>

              <div class="x_content">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Logo</th>
                        <th>Bank</th>
                        <th>Phone</th>
                        <th>Charges (%)</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th style="width:150px;">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($banks as $bank)
                        @php
                          $isActive = strtoupper($bank->status ?? 'INACTIVE') === 'ACTIVE';
                        @endphp
                        <tr>
                          <td style="width:80px;">
                            @if($bank->logo)
                              <img src="{{ asset('storage/'.$bank->logo) }}" width="55" style="border-radius:8px;">
                            @else
                              <span class="text-muted">N/A</span>
                            @endif
                          </td>
                          <td>{{ $bank->payment_owner }}</td>
                          <td>{{ $bank->phone_number ?? '—' }}</td>
                          <td>{{ number_format((float)$bank->charges, 2) }}</td>
                          <td>{{ number_format((float)$bank->balance, 2) }}</td>
                          <td>
                            <span class="badge {{ $isActive ? 'badge-success' : 'badge-secondary' }}">
                              {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
                            </span>
                          </td>

                          <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">

                              {{-- Edit --}}
                              <a href="{{ route('admin.banks.edit', $bank->id) }}" class="btn btn-primary" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a>

                              {{-- Toggle --}}
                              <form action="{{ route('admin.banks.toggle', $bank->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                  onclick="return confirm('Are you sure you want to {{ $isActive ? 'deactivate' : 'activate' }} this bank?')"
                                  title="Toggle Status">
                                  <i class="fa {{ $isActive ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                              </form>

                              {{-- Delete --}}
                              <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                  onclick="return confirm('Delete this bank? This cannot be undone.')"
                                  title="Delete">
                                  <i class="fa fa-trash"></i>
                                </button>
                              </form>

                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="7" class="text-center text-muted">No banks registered yet.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>
@endsection
