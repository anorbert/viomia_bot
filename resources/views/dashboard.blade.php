@extends('layouts.admin')

@section('content')
<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Parking Management Dashboard</h3>
    </div>
  </div>

  <div class="clearfix"></div>

  {{-- Top Tiles --}}
  <div class="row tile_count">
    <div class="col-md-2 col-sm-4 tile_stats_count">
      <span class="count_top"><i class="fa fa-car"></i> Total Parking Slots</span>
      <div class="count">{{ $totalSlots ?? '120' }}</div>
      <span class="count_bottom"><i class="green">+5 </i> New This Week</span>
    </div>
    <div class="col-md-2 col-sm-4 tile_stats_count">
      <span class="count_top"><i class="fa fa-car-side"></i> Occupied Slots</span>
      <div class="count">{{ $occupiedSlots ?? '87' }}</div>
      <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i> 5% </i> Today</span>
    </div>
    <div class="col-md-4 col-sm-4 tile_stats_count">
      <span class="count_top"><i class="fa fa-money-bill-wave"></i> Total Daily Revenue</span>
      <div class="count green">{{ number_format($totalRevenue ?? 0) }} Rwf</div>
      <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i></i> Today</span>
    </div>
    <div class="col-md-2 col-sm-4 tile_stats_count">
      <span class="count_top"><i class="fa fa-ticket-alt"></i> Active Tickets</span>
      <div class="count">{{ $activeTickets ?? '175' }}</div>
      <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>2% </i> vs. Last Week</span>
    </div>
  </div>

  {{-- Quick Reports Table --}}
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Quick Parking Reports</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Report Type</th>
                <th>Value</th>
                <th>Period</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><i class="fa fa-coins"></i> Today's Revenue</td>
                <td><strong>{{ number_format($todaysRevenue ?? 0) }} RWF</strong></td>
                <td>Today</td>
              </tr>
              <tr>
                <td><i class="fa fa-receipt"></i> Today's Transactions</td>
                <td><strong>{{ $todaysTransactions ?? 0 }}</strong></td>
                <td>Today</td>
              </tr>
              <tr>
                <td><i class="fa fa-receipt"></i> MOMO Revenue </td>
                <td><strong>{{ $momo ?? 0 }}</strong></td>
                <td>Today</td>
              </tr>
              <tr>
                <td><i class="fa fa-receipt"></i> CASH Revenue</td>
                <td><strong>{{ $cash ?? 0 }}</strong></td>
                <td>Today</td>
              </tr>
              <tr>
                <td><i class="fa fa-clock"></i> Avg. Parking Duration</td>
                <td><strong>{{ round($avgDuration ?? 0, 1) }} minutes</strong></td>
                <td>Today</td>
              </tr>
              <tr>
                <td><i class="fa fa-car"></i> Exempted Vehicles</td>
                <td><strong>{{ $exemptedCount ?? 0 }}</strong></td>
                <td>Currently Active</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Occupancy by Zone Chart
  const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
  new Chart(occupancyCtx, {
    type: 'bar',
    data: {
      labels: @json($zoneNames ?? []),
      datasets: [{
        label: 'Occupied Slots',
        data: @json($occupancyCounts ?? []),
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Revenue Trends Chart
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: @json($months ?? []),
      datasets: [{
        label: 'Revenue (RWF)',
        data: @json($revenues ?? []),
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
@endsection
