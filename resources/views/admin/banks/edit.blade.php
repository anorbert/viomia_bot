@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">

      <div class="x_title">
        <h2>Edit Bank <small>{{ $bank->payment_owner }}</small></h2>
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

        <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Bank Name</label>
              <input type="text" class="form-control" name="name"
                     value="{{ old('name', $bank->payment_owner) }}">
            </div>

            <div class="col-md-6 form-group">
              <label>Support Phone</label>
              <input type="text" class="form-control" name="phone"
                     value="{{ old('phone', $bank->phone_number) }}">
            </div>

            <div class="col-md-6 form-group">
              <label>Charges (%)</label>
              <input type="number" step="0.01" class="form-control" name="charges"
                     value="{{ old('charges', $bank->charges) }}">
            </div>

            <div class="col-md-6 form-group">
              <label>Replace Logo (optional)</label>
              <input type="file" class="form-control" name="logo" accept="image/*">
              @if($bank->logo)
                <small class="text-muted d-block mt-2">
                  Current:
                  <img src="{{ asset('storage/'.$bank->logo) }}" width="50" style="border-radius:8px;">
                </small>
              @endif
            </div>
          </div>

          <div class="form-group text-right">
            <a href="{{ route('admin.banks.index') }}" class="btn btn-default">Back</a>
            <button class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
