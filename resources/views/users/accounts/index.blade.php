@extends('layouts.user')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 40px 20px;">
    <div class="container" style="max-width: 1400px;">
        
        {{-- Professional Header with Breadcrumb --}}
        <div class="justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1 font-weight-bold text-dark" style="font-size: 32px;">
                    <i class="fa fa-university mr-2 text-primary"></i> My Trading Accounts
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-primary">Dashboard</a></li>
                        <li class="breadcrumb-item active">Trading Accounts</li>
                    </ol>
                </nav>
            </div>
            <button class="btn text-white shadow-sm border-0" 
                    data-toggle="modal" 
                    data-target="#addAccountModal"
                    style="background: linear-gradient(45deg, #667eea, #764ba2); font-weight: 600; border-radius: 8px; padding: 12px 24px;">
                <i class="fa fa-plus-circle mr-2"></i> Connect Account
            </button>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center py-3 mb-4" role="alert" style="border-radius: 10px;">
                <i class="fa fa-check-circle fa-lg mr-3" style="color: #28a745;"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="close ml-auto" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-3 mb-4" style="border-radius: 10px;">
                <div class="d-flex align-items-start">
                    <i class="fa fa-exclamation-circle fa-lg mr-3 mt-1" style="color: #dc3545;"></i>
                    <div>
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="card-body p-0">
                {{-- Search & Filter Bar --}}
                <div style="background: #fcfcfc; padding: 20px; border-bottom: 1px solid #f1f1f1; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="flex: 1; min-width: 250px;">
                        <div style="position: relative;">
                            <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                            <input type="text" 
                                   id="tableSearch" 
                                   class="form-control" 
                                   placeholder="Search by login, platform, server..."
                                   style="padding-left: 36px; border-radius: 8px; border: 1px solid #e5e5e5;">
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="text-align: right;">
                            <div class="text-muted small">Total Accounts</div>
                            <div class="font-weight-bold text-dark" style="font-size: 18px;">{{ $accounts->count() }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div class="text-muted small">Active Accounts</div>
                            <div class="font-weight-bold text-success" style="font-size: 18px;">{{ $accounts->where('active', true)->count() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="accountsTable" style="width:100%">
                        <thead style="background-color: #fcfcfc; border-bottom: 2px solid #f1f1f1;">
                            <tr>
                                <th class="text-uppercase small font-weight-bold text-muted px-4 py-3" style="width: 60px;">#</th>
                                <th class="text-uppercase small font-weight-bold text-muted">Account & Details</th>
                                <th class="text-uppercase small font-weight-bold text-muted text-center">Type</th>
                                <th class="text-uppercase small font-weight-bold text-muted">Platform Info</th>
                                <th class="text-uppercase small font-weight-bold text-muted text-center">Balance/Equity</th>
                                <th class="text-uppercase small font-weight-bold text-muted text-center">Connection</th>
                                <th class="text-uppercase small font-weight-bold text-muted text-center">Health</th>
                                <th class="text-uppercase small font-weight-bold text-muted text-right px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($accounts as $key => $acc)
                            <tr style="transition: background 0.3s ease; border-bottom: 1px solid #f1f1f1; cursor: pointer;" class="account-row" data-id="{{ $acc->id }}">
                                {{-- Serial Number --}}
                                <td class="px-4">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-light text-muted font-weight-bold" 
                                         style="width: 36px; height: 36px; font-size: 0.85rem; border: 1px solid #eee;">
                                        {{ $key + 1 }}
                                    </div>
                                </td>
                                
                                {{-- Account Login --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center rounded" 
                                             style="width: 44px; height: 44px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-right: 12px;">
                                            <i class="fa fa-briefcase text-white" style="font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $acc->login }}</div>
                                            @php
                                                $meta = $acc->meta ?? [];
                                            @endphp
                                            <div class="text-muted extra-small" style="font-size: 0.75rem; margin-top: 3px;">
                                                @if($meta['currency'] ?? null)
                                                    <i class="fa fa-dollar mr-1"></i> {{ $meta['currency'] }}
                                                @endif
                                                @if($meta['leverage'] ?? null)
                                                    <span class="mx-1">•</span>
                                                    <i class="fa fa-bolt mr-1"></i> {{ $meta['leverage'] }} Leverage
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Account Type --}}
                                <td class="text-center">
                                    @if(strtoupper($acc->account_type ?? '') === 'DEMO')
                                        <span class="badge badge-pill border-info text-info px-3 py-2" 
                                              style="background-color: #f0faff; font-size: 10px; border: 1px solid !important; font-weight: 600;">
                                            <i class="fa fa-flask mr-1"></i> DEMO
                                        </span>
                                    @else
                                        <span class="badge badge-pill border-warning text-warning px-3 py-2" 
                                              style="background-color: #fffdf0; font-size: 10px; border: 1px solid !important; color: #856404 !important; font-weight: 600;">
                                            <i class="fa fa-shield mr-1"></i> REAL
                                        </span>
                                    @endif
                                </td>

                                {{-- Platform Details --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(strtoupper($acc->platform) === 'MT5')
                                            <i class="fa fa-windows text-info mr-2" style="font-size: 16px;"></i>
                                        @elseif(strtoupper($acc->platform) === 'MT4')
                                            <i class="fa fa-windows text-secondary mr-2" style="font-size: 16px;"></i>
                                        @else
                                            <i class="fa fa-code-branch text-success mr-2" style="font-size: 16px;"></i>
                                        @endif
                                        <div>
                                            <div class="text-dark font-weight-bold" style="font-size: 0.85rem;">{{ strtoupper($acc->platform) }}</div>
                                            <div class="text-muted extra-small" style="font-size: 0.7rem;">
                                                <i class="fa fa-server mr-1"></i> {{ $acc->server }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Account Balance & Equity --}}
                                <td class="text-center">
                                    @php
                                        $snapshot = $acc->snapshots ? $acc->snapshots : null;
                                        $balance = $snapshot ? $snapshot->balance : 0;
                                        $equity = $snapshot ? $snapshot->equity : 0;
                                        $margin = $snapshot ? $snapshot->margin : 0;
                                        $marginUsage = $margin > 0 ? min(100, ($margin / $equity) * 100) : 0;
                                        $healthColor = $marginUsage > 80 ? '#dc3545' : ($marginUsage > 50 ? '#ffc107' : '#28a745');
                                    @endphp
                                    <div style="font-size: 0.85rem;">
                                        <div class="font-weight-bold text-dark">
                                            <i class="fa fa-dollar-sign mr-1"></i> {{ number_format($balance, 2) }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            Equity: {{ number_format($equity, 2) }}
                                        </div>
                                        <div style="margin-top: 4px;">
                                            <small class="text-muted">Margin:</small>
                                            <div class="progress" style="height: 6px; border-radius: 3px; background: #f0f0f0;">
                                                <div class="progress-bar" style="width: {{ $marginUsage }}%; background-color: {{ $healthColor }}; border-radius: 3px;"></div>
                                            </div>
                                            <small style="font-size: 0.7rem; color: {{ $healthColor }};">{{ number_format($marginUsage, 1) }}%</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Connection Status --}}
                                <td class="text-center">
                                    @if($acc->connected)
                                        <span class="badge badge-pill border-success text-success px-3 py-2" 
                                              style="background-color: #f1fbf3; font-size: 0.65rem; border: 1px solid; font-weight: 600;">
                                            <i class="fa fa-circle mr-1"></i> CONNECTED
                                        </span>
                                    @else
                                        <span class="badge badge-pill border-danger text-danger px-3 py-2" 
                                              style="background-color: #fff5f5; font-size: 0.65rem; border: 1px solid; font-weight: 600;">
                                            <i class="fa fa-circle mr-1"></i> OFFLINE
                                        </span>
                                    @endif
                                </td>

                                {{-- Bot Health Status --}}
                                <td class="text-center">
                                    @php
                                        // Determine health based on multiple factors
                                        $lastStatusChange = \App\Models\EaStatusChange::where('account_id', $acc->id)
                                            ->orderBy('changed_at', 'desc')
                                            ->first();
                                        
                                        $isHealthy = true;
                                        $healthIcon = 'fa-heartbeat';
                                        $healthColor = 'text-success';
                                        $healthBg = '#f1fbf3';
                                        $healthText = 'HEALTHY';
                                        
                                        if($lastStatusChange) {
                                            if(in_array($lastStatusChange->status, ['stopped', 'error', 'paused'])) {
                                                $isHealthy = false;
                                                $healthIcon = 'fa-exclamation-triangle';
                                                $healthColor = 'text-danger';
                                                $healthBg = '#fff5f5';
                                                $healthText = strtoupper($lastStatusChange->status);
                                            }
                                        }
                                    @endphp
                                    <span class="badge badge-pill border-{{ $isHealthy ? 'success' : 'danger' }} {{ $healthColor }} px-3 py-2" 
                                          style="background-color: {{ $healthBg }}; font-size: 0.65rem; border: 1px solid; font-weight: 600;">
                                        <i class="fa {{ $healthIcon }} mr-1"></i> {{ $healthText }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-right px-4">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-white text-info border shadow-sm mr-2 editBtn" 
                                                style="border-radius: 6px;" 
                                                title="Edit Account"
                                                data-id="{{ $acc->id }}"
                                                data-login="{{ $acc->login }}"
                                                data-platform="{{ $acc->platform }}"
                                                data-server="{{ $acc->server }}"
                                                data-type="{{ $acc->account_type }}"
                                                data-currency="{{ $meta['currency'] ?? '' }}"
                                                data-leverage="{{ $meta['leverage'] ?? '' }}"
                                                data-toggle="modal"
                                                data-target="#editAccountModal">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-white text-warning border shadow-sm mr-2 toggleBtn" 
                                                style="border-radius: 6px;" 
                                                title="Toggle Active Status"
                                                data-id="{{ $acc->id }}">
                                            <i class="fa fa-power-off"></i>
                                        </button>

                                        <form method="POST" action="{{ route('user.accounts.destroy', $acc->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-white text-danger border shadow-sm" 
                                                    style="border-radius: 6px;" 
                                                    title="Delete Account"
                                                    onclick="return confirm('Are you sure you want to remove this account? This action cannot be undone.')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- EXPANDABLE ACCOUNT DETAILS PANEL --}}
                            <tr class="detail-row" id="detail-{{ $acc->id }}" style="display: none;">
                                <td colspan="8" style="padding: 0; background: #f8f9fa; border: none;">
                                    <div style="padding: 25px; margin: 10px;">
                                        <div class="row">
                                            {{-- Account Snapshot Card --}}
                                            <div class="col-md-4">
                                                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                                                    <div class="card-header bg-light border-bottom" style="border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                        <h6 class="mb-0 font-weight-bold">
                                                            <i class="fa fa-chart-bar mr-2"></i> Account Metrics
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @php
                                                            $snapshot = $acc->snapshots;
                                                        @endphp
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Balance</small>
                                                            <h5 class="text-dark mb-0">{{ $snapshot ? '$' . number_format($snapshot->balance, 2) : 'N/A' }}</h5>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Equity</small>
                                                            <h5 class="text-dark mb-0">{{ $snapshot ? '$' . number_format($snapshot->equity, 2) : 'N/A' }}</h5>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Free Margin</small>
                                                            <h5 class="text-dark mb-0">{{ $snapshot ? '$' . number_format($snapshot->free_margin, 2) : 'N/A' }}</h5>
                                                        </div>
                                                        <div style="margin-bottom: 0;">
                                                            <small class="text-muted d-block">Max Drawdown</small>
                                                            <h5 class="mb-0" style="color: {{ ($snapshot && $snapshot->drawdown > 20) ? '#dc3545' : '#28a745' }};">
                                                                {{ $snapshot && $snapshot->drawdown ? number_format($snapshot->drawdown, 2) . '%' : 'N/A' }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Trading Activity Card --}}
                                            <div class="col-md-4">
                                                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                                                    <div class="card-header bg-light border-bottom" style="border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                                        <h6 class="mb-0 font-weight-bold">
                                                            <i class="fa fa-tachometer mr-2"></i> Trading Activity
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @php
                                                            $openPositions = \App\Models\PositionUpdate::where('account_id', $acc->id)->count();
                                                            $dailyTrades = \App\Models\TradeLog::where('account_id', $acc->id)
                                                                ->whereDate('created_at', today())
                                                                ->count();
                                                            $dailySummary = \App\Models\DailySummary::where('account_id', $acc->id)
                                                                ->whereDate('summary_date', today())
                                                                ->first();
                                                        @endphp
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Open Positions</small>
                                                            <h5 class="text-dark mb-0">{{ $openPositions }}</h5>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Today's Trades</small>
                                                            <h5 class="text-dark mb-0">{{ $dailyTrades }}</h5>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Today's P/L</small>
                                                            <h5 class="mb-0" style="color: {{ ($dailySummary && $dailySummary->daily_pl >= 0) ? '#28a745' : '#dc3545' }};">
                                                                {{ $dailySummary ? '$' . number_format($dailySummary->daily_pl, 2) : 'N/A' }}
                                                            </h5>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Win Rate (Today)</small>
                                                            <h5 class="text-dark mb-0">{{ $dailySummary ? number_format($dailySummary->win_rate_percent, 1) : 'N/A' }}%</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Bot Status Card --}}
                                            <div class="col-md-4">
                                                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                                                    <div class="card-header bg-light border-bottom" style="border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                                        <h6 class="mb-0 font-weight-bold">
                                                            <i class="fa fa-robot mr-2"></i> Bot Status
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @php
                                                            $lastStatusChange = \App\Models\EaStatusChange::where('account_id', $acc->id)
                                                                ->orderBy('changed_at', 'desc')
                                                                ->first();
                                                        @endphp
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Bot State</small>
                                                            @if($lastStatusChange)
                                                                <h5 class="mb-0">
                                                                    <span class="badge badge-{{ in_array($lastStatusChange->status, ['running', 'active']) ? 'success' : (in_array($lastStatusChange->status, ['stopped', 'error']) ? 'danger' : 'warning') }}" style="font-size: 12px; padding: 6px 12px;">
                                                                        {{ strtoupper($lastStatusChange->status) }}
                                                                    </span>
                                                                </h5>
                                                            @else
                                                                <h5 class="text-muted mb-0">Unknown</h5>
                                                            @endif
                                                        </div>
                                                        @if($lastStatusChange)
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Consecutive Losses</small>
                                                            <h5 class="mb-0" style="color: {{ $lastStatusChange->consecutive_losses > 3 ? '#dc3545' : '#28a745' }};">
                                                                {{ $lastStatusChange->consecutive_losses }}
                                                            </h5>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <small class="text-muted d-block">Open Positions (Bot)</small>
                                                            <h5 class="text-dark mb-0">{{ $lastStatusChange->positions_open }}</h5>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Last Changed</small>
                                                            <small class="text-dark" style="font-weight: 500;">
                                                                {{ $lastStatusChange->changed_at ? $lastStatusChange->changed_at->diffForHumans() : 'N/A' }}
                                                            </small>
                                                        </div>
                                                        @else
                                                        <p class="text-muted" style="font-size: 13px;">No status data available yet</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Additional Info Row --}}
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                                                    <div class="card-header bg-light border-bottom" style="border-radius: 10px 10px 0 0;">
                                                        <h6 class="mb-0 font-weight-bold text-dark">
                                                            <i class="fa fa-history mr-2"></i> Connection Details & Status History
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row small">
                                                            <div class="col-md-3">
                                                                <span class="text-muted d-block mb-1">Connection Status</span>
                                                                <span class="badge badge-{{ $acc->connected ? 'success' : 'danger' }}">
                                                                    {{ $acc->connected ? '🟢 Connected' : '🔴 Offline' }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span class="text-muted d-block mb-1">Active Status</span>
                                                                <span class="badge badge-{{ $acc->active ? 'success' : 'secondary' }}">
                                                                    {{ $acc->active ? '✓ Active' : '✗ Inactive' }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span class="text-muted d-block mb-1">Verification</span>
                                                                <span class="badge badge-{{ $acc->is_verified ? 'success' : 'warning' }}">
                                                                    {{ $acc->is_verified ? '✓ Verified' : '⏳ Pending' }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span class="text-muted d-block mb-1">Last Updated</span>
                                                                <span class="text-dark" style="font-weight: 500;">
                                                                    {{ $acc->updated_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="color: #999;">
                                        <i class="fa fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-0" style="font-size: 16px;">No trading accounts connected yet</p>
                                        <p class="text-muted" style="font-size: 14px;">Click the "Connect Account" button to add your first trading account</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('user.accounts.store') }}" class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            @csrf
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-plus-circle mr-2"></i> Connect New Account
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Trading Platform</label>
                        <select class="form-control" name="platform" required style="border-radius: 6px; border: 1px solid #e5e5e5;">
                            <option value="MT5">MetaTrader 5 (MT5)</option>
                            <option value="MT4">MetaTrader 4 (MT4)</option>
                            <option value="cTrader">cTrader</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Account Type</label>
                        <select class="form-control" name="account_type" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                            <option value="">Select Type</option>
                            <option value="Real">Real Account</option>
                            <option value="Demo">Demo Account</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Server Address</label>
                    <input class="form-control" name="server" required placeholder="e.g., RoboForex-Demo" style="border-radius: 6px; border: 1px solid #e5e5e5;" value="{{ old('server') }}">
                    <small class="text-muted">Enter the server name or address provided by your broker</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Account Login</label>
                    <input class="form-control" name="login" required placeholder="e.g., 123456789" style="border-radius: 6px; border: 1px solid #e5e5e5;" value="{{ old('login') }}">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Password</label>
                    <input class="form-control" name="password" type="password" required placeholder="Account password" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    <small class="text-muted"><i class="fa fa-shield mr-1"></i> Your password is encrypted and secure. It's never displayed in the UI.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Currency (Optional)</label>
                        <input class="form-control" name="meta[currency]" placeholder="e.g., USD" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Leverage (Optional)</label>
                        <input class="form-control" name="meta[leverage]" placeholder="e.g., 1:500" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #fcfcfc; border-top: 1px solid #f1f1f1;">
                <button class="btn btn-light" data-dismiss="modal" type="button" style="border-radius: 6px;">Cancel</button>
                <button class="btn text-white" type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 6px; border: none; padding: 8px 24px; font-weight: 600;">
                    <i class="fa fa-check mr-1"></i> Connect Account
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" id="editForm" class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            @csrf
            @method('PUT')
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); color: white; border: none;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-edit mr-2"></i> Edit Account
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Trading Platform</label>
                        <select class="form-control" name="platform" id="editPlatform" required style="border-radius: 6px; border: 1px solid #e5e5e5;">
                            <option value="MT5">MetaTrader 5 (MT5)</option>
                            <option value="MT4">MetaTrader 4 (MT4)</option>
                            <option value="cTrader">cTrader</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Account Type</label>
                        <select class="form-control" name="account_type" id="editType" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                            <option value="">Select Type</option>
                            <option value="Real">Real Account</option>
                            <option value="Demo">Demo Account</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Server Address</label>
                    <input class="form-control" name="server" id="editServer" required style="border-radius: 6px; border: 1px solid #e5e5e5;">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Account Login</label>
                    <input class="form-control" id="editLogin" disabled style="border-radius: 6px; border: 1px solid #e5e5e5; background: #f8f9fa;">
                    <small class="text-muted"><i class="fa fa-info-circle mr-1"></i> Login cannot be changed. Remove and reconnect if needed.</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" style="color: #2c3e50;">Password (Optional)</label>
                    <input class="form-control" name="password" type="password" placeholder="Leave blank to keep current password" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    <small class="text-muted">Only fill this if you want to update the password</small>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Currency</label>
                        <input class="form-control" name="meta[currency]" id="editCurrency" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold" style="color: #2c3e50;">Leverage</label>
                        <input class="form-control" name="meta[leverage]" id="editLeverage" style="border-radius: 6px; border: 1px solid #e5e5e5;">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #fcfcfc; border-top: 1px solid #f1f1f1;">
                <button class="btn btn-light" data-dismiss="modal" type="button" style="border-radius: 6px;">Cancel</button>
                <button class="btn text-white" type="submit" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); border-radius: 6px; border: none; padding: 8px 24px; font-weight: 600;">
                    <i class="fa fa-save mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .pulse-green {
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .btn-group .btn {
        border: 1px solid #e5e5e5 !important;
        background: white !important;
        padding: 6px 10px !important;
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    }
</style>

@endsection

@push('scripts')
<script>
(function(){

    // Expandable rows functionality
    document.querySelectorAll('.account-row').forEach(row => {
        row.addEventListener('click', function(e){
            // Don't expand if clicking action buttons
            if(e.target.closest('.btn-group')) return;
            
            const accountId = this.dataset.id;
            const detailRow = document.getElementById('detail-' + accountId);
            
            if(detailRow) {
                const isVisible = detailRow.style.display !== 'none';
                // Close all other details
                document.querySelectorAll('.detail-row').forEach(d => d.style.display = 'none');
                // Toggle current
                detailRow.style.display = isVisible ? 'none' : 'table-row';
                
                // Highlight row
                document.querySelectorAll('.account-row').forEach(r => r.style.backgroundColor = '');
                if(!isVisible) {
                    this.style.backgroundColor = '#f0f4ff';
                }
            }
        });
    });

    // Search functionality
    const search = document.getElementById('tableSearch');
    const table  = document.getElementById('accountsTable');
    if(search && table){
        search.addEventListener('keyup', function(){
            const q = this.value.toLowerCase();
            table.querySelectorAll('tbody tr.account-row').forEach(tr => {
                tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // Edit modal population
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

    // Toggle account active status via AJAX
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
                
                if(!res.ok) {
                    console.error('Toggle failed:', res.statusText);
                    return;
                }

                const data = await res.json();
                const badge = document.getElementById('status-badge-' + id);

                if(badge){
                    if(data.active) {
                        badge.classList.remove('border-secondary', 'text-secondary');
                        badge.classList.add('border-success', 'text-success');
                        badge.innerHTML = '<i class="fa fa-circle mr-1 pulse-green"></i> ACTIVE';
                        badge.style.backgroundColor = '#f1fbf3';
                    } else {
                        badge.classList.remove('border-success', 'text-success');
                        badge.classList.add('border-secondary', 'text-secondary');
                        badge.innerHTML = '<i class="fa fa-circle mr-1"></i> INACTIVE';
                        badge.style.backgroundColor = '#f8f9fa';
                    }
                }
            }catch(e){
                console.error('Error toggling account:', e);
            }
        });
    });

})();
</script>
@endpush

