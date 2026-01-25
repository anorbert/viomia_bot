@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Subscription Plan</h4>
        <a href="{{ route('admin.subscription_plans.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Fix the errors below:</strong>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.subscription_plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.subscription_plans._form', ['plan' => $plan])

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                    <a href="{{ route('admin.subscription_plans.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
