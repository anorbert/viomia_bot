@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Payment Reports</h2></div>

    <div class="x_content">
        <h3>Total Revenue: ${{ number_format($total, 2) }}</h3>

        <canvas id="paymentChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('paymentChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($months) !!},
        datasets: [{
            label: 'Monthly Revenue',
            data: {!! json_encode($values) !!}
        }]
    }
});
</script>
@endpush
