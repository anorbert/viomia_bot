@extends('layouts.admin')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Header --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-8 col-12">
            <h4 class="mb-1">Bot Global Settings</h4>
            <div class="text-muted small">
                Trading engine configuration & safety limits
                <span class="mx-2">•</span>
                Last updated:
                <strong>{{ optional($settings->updated_at)->format('d M Y H:i') }}</strong>
            </div>
        </div>
        <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="fa fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Validation errors (optional but recommended) --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1"><i class="fa fa-exclamation-triangle"></i> Please fix the errors below:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.bots.settings.update', $settings->id) }}">
        @csrf

        <div class="row g-3">

            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">

                {{-- General --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">
                                <i class="fa fa-sliders"></i> General
                            </div>
                            <div class="text-muted small">Main switches for bot running & logs.</div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">Enable Trading Bot</div>
                                <div class="text-muted small">Turn the bot on/off globally.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="bot_enabled"
                                       id="bot_enabled"
                                       {{ $settings->bot_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3">
                            <div>
                                <div class="fw-semibold">Debug Mode</div>
                                <div class="text-muted small">Enable verbose logs for troubleshooting.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="debug_mode"
                                       id="debug_mode"
                                       {{ $settings->debug_mode ? 'checked' : '' }}>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Signal Fetching --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white border-0">
                        <div class="fw-bold"><i class="fa fa-clock"></i> Signal Fetching</div>
                        <div class="text-muted small">Control polling frequency and spread protection.</div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Signal Check Interval</label>
                                <div class="input-group">
                                    <input type="number"
                                           min="1"
                                           class="form-control"
                                           name="signal_check_interval"
                                           value="{{ $settings->signal_check_interval }}">
                                    <span class="input-group-text">sec</span>
                                </div>
                                <div class="text-muted small mt-1">Recommended: 3–10 seconds.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Max Allowed Spread</label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control"
                                           name="max_spread_points"
                                           value="{{ $settings->max_spread_points }}">
                                    <span class="input-group-text">points</span>
                                </div>
                                <div class="text-muted small mt-1">If spread is higher, bot will skip entries.</div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Risk Management --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white border-0">
                        <div class="fw-bold"><i class="fa fa-chart-line"></i> Risk Management</div>
                        <div class="text-muted small">Daily limits and per-trade risk control.</div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Risk per Trade</label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="risk_per_trade"
                                           value="{{ $settings->risk_per_trade }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="text-muted small mt-1">Example: 1.00 means 1% per trade.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Max Trades per Day</label>
                                <input type="number"
                                       class="form-control"
                                       name="max_trades_per_day"
                                       value="{{ $settings->max_trades_per_day }}">
                                <div class="text-muted small mt-1">Stop trading after reaching this number.</div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">

                {{-- News Filter --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <div class="fw-bold"><i class="fa fa-newspaper"></i> News Filter</div>
                        <div class="text-muted small">Avoid trading during high-impact news.</div>
                    </div>

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between pb-3 border-bottom">
                            <div>
                                <div class="fw-semibold">Enable News Filter</div>
                                <div class="text-muted small">Block trading around news events.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="use_news_filter"
                                       id="use_news_filter"
                                       {{ $settings->use_news_filter ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Block Before News</label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control"
                                       name="block_before_news_minutes"
                                       value="{{ $settings->block_before_news_minutes }}">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Block After News</label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control"
                                       name="block_after_news_minutes"
                                       value="{{ $settings->block_after_news_minutes }}">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Filter Currencies</label>
                            <input type="text"
                                   class="form-control"
                                   name="filter_currencies"
                                   value="{{ $settings->filter_currencies }}"
                                   placeholder="USD,EUR,GBP">
                            <div class="text-muted small mt-1">Comma separated list (e.g., USD,EUR,GBP).</div>
                        </div>

                    </div>
                </div>

                {{-- Quick tips --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="fw-bold mb-2"><i class="fa fa-lightbulb-o"></i> Tips</div>
                        <ul class="small text-muted mb-0">
                            <li>Keep interval above 3 seconds to reduce API load.</li>
                            <li>Use spread limits for volatile sessions.</li>
                            <li>News filter is recommended for XAUUSD & major pairs.</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

        {{-- Sticky Save Bar --}}
        <div class="position-sticky" style="bottom: 0; z-index: 1020;">
            <div class="card shadow-sm mt-3 border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="fa fa-info-circle"></i> Changes take effect on the next EA settings fetch.
                    </div>
                    <button class="btn btn-success px-4">
                        <i class="fa fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
