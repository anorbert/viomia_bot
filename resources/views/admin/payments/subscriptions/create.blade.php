@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create Subscription Plan</h4>
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
            <form action="{{ route('admin.subscription_plans.store') }}" method="POST">
                @csrf
                @include('admin.payments.subscriptions.forms')
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Save Plan</button>
                    <a href="{{ route('admin.subscription_plans.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
