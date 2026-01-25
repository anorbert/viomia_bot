@extends('layouts.admin')

@section('content')
<div class="container">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Subscription Plans</h4>
            <div class="text-muted small">Manage pricing, duration, and plan features.</div>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="{{ route('admin.subscription_plans.create') }}" class="btn btn-success">
                <i class="fa fa-plus me-1"></i> Create Plan
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    @php
        // If you already computed these in controller, replace with those variables.
        $totalPlans = $plans->total();
        $activePlans = \App\Models\SubscriptionPlan::where('is_active', 1)->count();
        $disabledPlans = \App\Models\SubscriptionPlan::where('is_active', 0)->count();
    @endphp

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Plans</div>
                        <div class="fs-4 fw-bold">{{ $totalPlans }}</div>
                    </div>
                    <div class="rounded-circle p-3 bg-light">
                        <i class="fa fa-list-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Active</div>
                        <div class="fs-4 fw-bold">{{ $activePlans }}</div>
                    </div>
                    <div class="rounded-circle p-3 bg-light">
                        <i class="fa fa-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Disabled</div>
                        <div class="fs-4 fw-bold">{{ $disabledPlans }}</div>
                    </div>
                    <div class="rounded-circle p-3 bg-light">
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.subscription_plans.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" name="q" class="form-control"
                               value="{{ request('q') }}"
                               placeholder="Search by name or slug...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="per_page" class="form-select">
                        @php $pp = request('per_page', 20); @endphp
                        <option value="10" {{ $pp == 10 ? 'selected' : '' }}>10 / page</option>
                        <option value="20" {{ $pp == 20 ? 'selected' : '' }}>20 / page</option>
                        <option value="50" {{ $pp == 50 ? 'selected' : '' }}>50 / page</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">
                        Filter
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.subscription_plans.index') }}">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px;">#</th>
                            <th>Plan</th>
                            <th>Pricing</th>
                            <th>Interval</th>
                            <th>Duration</th>
                            <th>Features</th>
                            <th>Status</th>
                            <th class="text-end" style="width:120px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($plans as $plan)
                            @php
                                $featuresCount = is_array($plan->features) ? count($plan->features) : 0;
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $plan->id }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $plan->name }}</div>
                                    <div class="small text-muted">
                                        <span class="badge bg-secondary">{{ $plan->slug }}</span>
                                    </div>
                                </td>

                                <td class="fw-semibold">
                                    {{ $plan->currency }} {{ number_format((float)$plan->price, 2) }}
                                </td>

                                <td class="text-capitalize">
                                    <span class="badge bg-light text-dark border">
                                        {{ $plan->billing_interval }}
                                    </span>
                                </td>

                                <td>
                                    @if($plan->duration_days)
                                        <span class="badge bg-info-subtle text-dark border">
                                            {{ $plan->duration_days }} days
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($featuresCount > 0)
                                        <span class="badge bg-primary-subtle text-dark border">
                                            {{ $featuresCount }} feature{{ $featuresCount > 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="text-muted">No features</span>
                                    @endif
                                </td>

                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Disabled</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.subscription_plans.edit', $plan->id) }}">
                                                    <i class="fa fa-edit me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.subscription_plans.destroy', $plan->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Delete this plan? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="fa fa-folder-open fa-2x text-muted"></i>
                                    </div>
                                    <div class="fw-semibold">No subscription plans found</div>
                                    <div class="text-muted small mb-3">Create your first plan to start assigning subscriptions.</div>
                                    <a href="{{ route('admin.subscription_plans.create') }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus me-1"></i> Create Plan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        @if($plans->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $plans->firstItem() ?? 0 }} to {{ $plans->lastItem() ?? 0 }} of {{ $plans->total() }} plans
            </div>
            <div>
                {{ $plans->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
