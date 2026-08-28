{{-- FILE: resources/views/pos/dashboard.blade.php --}}
@extends('pos.layouts.pos_master')
@section('title', 'Point of Sales Dashboard')

@section('content')
<style>
  .pos-stats-card {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .pos-stats-card .label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
  }
  .pos-stats-card .value {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    margin: 4px 0 0 0;
  }
  .pos-stats-card .icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    flex-shrink: 0;
  }
  .pos-stats-card .icon-green { background: #ecfdf5; color: #10b981; }
  .pos-stats-card .icon-amber { background: #fffbeb; color: #f59e0b; }
  .pos-stats-card .icon-blue { background: #eff6ff; color: #3b82f6; }
  .pos-stats-card .icon-purple { background: #f5f3ff; color: #8b5cf6; }
  .pos-stats-card .icon-pink { background: #fdf2f8; color: #ec4899; }
  .pos-stats-card .icon-sky { background: #f0f9ff; color: #0ea5e9; }
  .pos-stats-card .icon-orange { background: #fff7ed; color: #f97316; }

  .pos-graph-box {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .pos-graph-box .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .pos-graph-box .header h4 {
    font-weight: 700;
    color: #111827;
    margin: 0;
  }
  .pos-graph-box .header .tag {
    font-size: 11px;
    padding: 4px 10px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 9999px;
    font-weight: 500;
  }
  .pos-graph-box .graph-placeholder {
    height: 256px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    border-radius: 12px;
    border: 2px dashed #e5e7eb;
    color: #9ca3af;
    position: relative;
  }
  .pos-graph-box .graph-placeholder canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .pos-title h1 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.025em;
    margin: 0;
  }
  .pos-title p {
    font-size: 14px;
    color: #6b7280;
    margin: 2px 0 0 0;
  }
</style>

<div class="pos-title mb-4">
  <h1>POS Dashboard</h1>
  <p>Real-time overview of your point of sales performance metrics.</p>
</div>

<!-- Row 1: 4 Stats Cards -->
<div class="row g-4 mb-4">
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="pos-stats-card">
      <div>
        <p class="label">Revenue</p>
        <h3 class="value">Rs. 0.00</h3>
      </div>
      <div class="icon icon-green">
        <i class="fa-solid fa-wallet"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="pos-stats-card">
      <div>
        <p class="label">Profit</p>
        <h3 class="value">Rs. 0.00</h3>
      </div>
      <div class="icon icon-amber">
        <i class="fa-solid fa-chart-line"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="pos-stats-card">
      <div>
        <p class="label">Orders</p>
        <h3 class="value">6</h3>
      </div>
      <div class="icon icon-blue">
        <i class="fa-solid fa-box-open"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="pos-stats-card">
      <div>
        <p class="label">Reviews</p>
        <h3 class="value">21</h3>
      </div>
      <div class="icon icon-purple">
        <i class="fa-solid fa-star"></i>
      </div>
    </div>
  </div>
</div>

<!-- Row 2: 3 Stats Cards -->
<div class="row g-4 mb-4">
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="pos-stats-card">
      <div>
        <p class="label">Contacts</p>
        <h3 class="value">59</h3>
      </div>
      <div class="icon icon-pink">
        <i class="fa-solid fa-envelope"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="pos-stats-card">
      <div>
        <p class="label">Riders</p>
        <h3 class="value">3</h3>
      </div>
      <div class="icon icon-sky">
        <i class="fa-solid fa-motorcycle"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="pos-stats-card">
      <div>
        <p class="label">Users</p>
        <h3 class="value">1,253</h3>
      </div>
      <div class="icon icon-orange">
        <i class="fa-solid fa-users"></i>
      </div>
    </div>
  </div>
</div>

<!-- Row 3: Graphs -->
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="pos-graph-box">
      <div class="header">
        <h4>Sales Overview Analytics</h4>
        <span class="tag">Live Active Tracking</span>
      </div>
      <div class="graph-placeholder">
        <canvas id="salesChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="pos-graph-box">
      <div class="header">
        <h4>Orders Lifecycle Breakdown</h4>
      </div>
      <div class="graph-placeholder">
        <canvas id="ordersChart"></canvas>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const ctx = document.getElementById('salesChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Sales',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: '#ff2d7a',
            backgroundColor: 'rgba(255, 45, 122, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    }

    // Orders Chart
    const ctx2 = document.getElementById('ordersChart');
    if (ctx2) {
      new Chart(ctx2, {
        type: 'doughnut',
        data: {
          labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
          datasets: [{
            data: [30, 25, 35, 10],
            backgroundColor: ['#ff2d7a', '#ff9a9e', '#4ade80', '#f87171'],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    }
  });
</script>
@endsection