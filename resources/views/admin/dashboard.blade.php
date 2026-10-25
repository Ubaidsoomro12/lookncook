{{-- FILE: resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .dashboard-wrapper {
        font-family: 'Inter', sans-serif;
        padding-bottom: 30px;
    }

    /* --- Page Title --- */
    .dash-title h1 {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin: 0;
    }
    .dash-title p {
        font-size: 14px;
        color: #64748b;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    /* --- Stat Cards --- */
    .stat-card-new {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        height: 100%;
    }
    .stat-card-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    .stat-card-new .info .label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 6px 0;
    }
    .stat-card-new .info .value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.1;
    }
    .stat-card-new .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .stat-card-new:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    /* Dynamic Icon Colors */
    .icon-green { background: #ecfdf5; color: #10b981; }
    .icon-amber { background: #fffbeb; color: #f59e0b; }
    .icon-blue { background: #eff6ff; color: #3b82f6; }
    .icon-purple { background: #f5f3ff; color: #8b5cf6; }
    .icon-pink { background: #fdf2f8; color: #ec4899; }
    .icon-sky { background: #f0f9ff; color: #0ea5e9; }
    .icon-orange { background: #fff7ed; color: #f97316; }
    .icon-teal { background: #f0fdfa; color: #14b8a6; }

    /* --- Graph Cards --- */
    .graph-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        height: 100%;
    }
    .graph-card .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .graph-card .header h4 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .graph-card .header .tag {
        font-size: 11px;
        padding: 4px 12px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="dashboard-wrapper">
    <div class="dash-title mb-4">
        <h1>Admin Dashboard</h1>
        <p>Central overview of your platform's performance metrics.</p>
    </div>

    <!-- Row 1: 4 Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Revenue</p>
                    <h3 class="value">Rs. {{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="icon-box icon-green">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Profit</p>
                    <h3 class="value">Rs. {{ number_format($totalProfit, 2) }}</h3>
                </div>
                <div class="icon-box icon-amber">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Orders</p>
                    <h3 class="value">{{ number_format($totalOrders) }}</h3>
                </div>
                <div class="icon-box icon-blue">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Reviews</p>
                    <h3 class="value">{{ number_format($totalReviews) }}</h3>
                </div>
                <div class="icon-box icon-purple">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: 3 Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Contacts</p>
                    <h3 class="value">{{ number_format($totalContacts) }}</h3>
                </div>
                <div class="icon-box icon-pink">
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Riders</p>
                    <h3 class="value">{{ number_format($totalRiders) }}</h3>
                </div>
                <div class="icon-box icon-sky">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-new">
                <div class="info">
                    <p class="label">Users</p>
                    <h3 class="value">{{ number_format($totalUsers) }}</h3>
                </div>
                <div class="icon-box icon-orange">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Graphs -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="graph-card">
                <div class="header">
                    <h4>Sales Overview Analytics</h4>
                    <span class="tag">Last 7 Days</span>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="graph-card">
                <div class="header">
                    <h4>Orders Lifecycle</h4>
                </div>
                <div class="chart-container">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. Sales Line Chart ---
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        
        // Create gradient for the line chart
        let gradient = salesCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(255, 45, 122, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 45, 122, 0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($chartDates),
                datasets: [{
                    label: 'Revenue (PKR)',
                    data: @json($chartSales),
                    borderColor: '#ff2d7a',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#ff2d7a',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // Makes the line curved and smooth
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#e2e8f0' },
                        ticks: { color: '#64748b', font: { family: 'Inter', size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: 'Inter', size: 12 } }
                    }
                }
            }
        });

        // --- 2. Orders Doughnut Chart ---
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        
        new Chart(ordersCtx, {
            type: 'doughnut',
            data: {
                labels: @json($statusLabels),
                datasets: [{
                    data: @json($statusCounts),
                    backgroundColor: [
                        '#3b82f6', // Blue
                        '#10b981', // Green
                        '#f59e0b', // Amber
                        '#ef4444', // Red
                        '#8b5cf6', // Purple
                        '#64748b'  // Gray
                    ],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Makes it a sleek thin ring
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: 'Inter', size: 12 },
                            color: '#475569'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection