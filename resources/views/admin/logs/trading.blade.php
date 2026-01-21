@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<style>
    .badge-buy { background: #28a745; }
    .badge-sell { background: #dc3545; }

    .profit-positive { color: #28a745; font-weight: bold; }
    .profit-negative { color: #dc3545; font-weight: bold; }
</style>
@endsection

@section('content')
<div class="x_panel">
    <div class="x_title">
        <h2>📊 Trading Logs</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="table-responsive">
            <table id="tradesTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Ticket</th>
                        <th>Type</th>
                        <th>Symbol</th>
                        <th>Lot</th>
                        <th>Entry($)</th>
                        <th>SL($)</th>
                        <th>TP</th>
                        <th>P/L ($)</th>
                        <th>Time</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($trades as $key => $log)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $log->ticket }}</strong></td>
                            <td>
                                <span class="badge {{ $log->type == 'buy' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($log->type) }}
                                </span>
                            </td>

                            <td><strong>{{ $log->symbol }}</strong></td>
                            <td><strong>{{ number_format($log->lots, 2) }}</strong></td>
                            <td class="text text{{ $log->profit >=0 ? '-info' : '-danger' }}">
                                {{ number_format($log->open_price, 2) }}
                            </td>
                            <td class="text text{{ $log->profit >=0 ? '-info' : '-danger' }}">
                                {{ number_format($log->sl, 2) }}
                            </td>
                            <td class="text text{{ $log->profit >=0 ? '-info' : '-danger' }}">
                                {{ number_format($log->tp, 2) }}
                            </td>

                            <td class="text text{{ $log->profit >=0 ? '-info' : '-danger' }}">
                                    {{ number_format($log->profit, 2) }}
                            </td>

                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

