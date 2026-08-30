{{-- FILE: resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('content')
<style>
  .dash-stats-card {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .dash-stats-card .label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
  }
  .dash-stats-card .value {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    margin: 4px 0 0 0;
  }
  .dash-stats-card .icon {
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
  .dash-stats-card .icon-green { background: #ecfdf5; color: #10b981; }
  .dash-stats-card .icon-amber { background: #fffbeb; color: #f59e0b; }
  .dash-stats-card .icon-blue { background: #eff6ff; color: #3b82f6; }
  .dash-stats-card .icon-purple { background: #f5f3ff; color: #8b5cf6; }
  .dash-stats-card .icon-pink { background: #fdf2f8; color: #ec4899; }
  .dash-stats-card .icon-sky { background: #f0f9ff; color: #0ea5e9; }
  .dash-stats-card .icon-orange { background: #fff7ed; color: #f97316; }

  .dash-graph-box {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .dash-graph-box .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .dash-graph-box .header h4 {
    font-weight: 700;
    color: #111827;
    margin: 0;
  }
  .dash-graph-box .header .tag {
    font-size: 11px;
    padding: 4px 10px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 9999px;
    font-weight: 500;
  }
  .dash-graph-box .placeholder {
    height: 256px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    border-radius: 12px;
    border: 2px dashed #e5e7eb;
    color: #9ca3af;
  }
  .dash-title h1 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.025em;
    margin: 0;
  }
  .dash-title p {
    font-size: 14px;
    color: #6b7280;
    margin: 2px 0 0 0;
  }
</style>

<div class="dash-title mb-4">
  <h1>Admin Dashboard</h1>
  <p>Central overview of your platform's performance metrics context profile.</p>
</div>

<!-- Row 1: 4 Stats Cards -->
<div class="row g-4 mb-4">
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-stats-card">
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
    <div class="dash-graph-box">
      <div class="header">
        <h4>Sales Overview Analytics</h4>
        <span class="tag">Live Active Tracking Data Matrix</span>
      </div>
      <div class="placeholder">
        [ Dynamic Graphical Data Line Engine Map Component Wrapper Frame Area ]
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="dash-graph-box">
      <div class="header">
        <h4>Orders Lifecycle Breakdown</h4>
      </div>
      <div class="placeholder">
        [ Doughnut Segment Distribution Lifecycle Viewport Area ]
      </div>
    </div>
  </div>
</div>
@endsection