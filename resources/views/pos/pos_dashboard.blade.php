{{-- FILE: resources/views/pos/pos_dashboard.blade.php --}}
@extends('pos.layouts.pos_master')
@section('title', 'POS Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .pos-dashboard-dark {
        font-family: 'Inter', sans-serif;
        background-color: #0f172a; 
        color: #f8fafc;
        padding: 20px 24px;
        min-height: 100vh;
        border-radius: 12px;
    }

    .pos-title h1 {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.5px;
        margin: 0;
    }
    .pos-title p {
        font-size: 14px;
        color: #94a3b8;
        margin: 4px 0 0 0;
    }

    /* --- Neon Stat Cards --- */
    .neon-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .clickable-card {
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .clickable-card:hover {
        border-color: #ff2d7a;
        box-shadow: 0 10px 25px rgba(255, 45, 122, 0.15);
    }

    .neon-card .info .label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 6px 0;
    }
    .neon-card .info .value {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        line-height: 1.1;
    }
    .neon-card .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    /* Neon Colors */
    .neon-green { background: rgba(16, 185, 129, 0.1); color: #34d399; box-shadow: 0 0 15px rgba(16, 185, 129, 0.2); }
    .neon-pink { background: rgba(255, 45, 122, 0.1); color: #ff2d7a; box-shadow: 0 0 15px rgba(255, 45, 122, 0.2); }
    .neon-blue { background: rgba(59, 130, 246, 0.1); color: #60a5fa; box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
    .neon-orange { background: rgba(249, 115, 22, 0.1); color: #fb923c; box-shadow: 0 0 15px rgba(249, 115, 22, 0.2); }
    .neon-purple { background: rgba(139, 92, 246, 0.1); color: #a78bfa; box-shadow: 0 0 15px rgba(139, 92, 246, 0.2); }
    .neon-yellow { background: rgba(245, 158, 11, 0.1); color: #fbbf24; box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); }

    /* --- Graph Cards --- */
    .dark-graph-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        height: 100%;
    }
    .dark-graph-card .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .dark-graph-card .header h4 {
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }
    .dark-graph-card .header .tag {
        font-size: 11px;
        padding: 4px 12px;
        background: #334155;
        color: #94a3b8;
        border-radius: 20px;
        font-weight: 600;
    }
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }

    /* =========================================
       MODAL STYLING
       ========================================= */
    .modal-content-dark {
        background-color: #0f172a;
        border: 1px solid #334155;
        border-radius: 20px;
        color: #f8fafc;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .modal-header-dark {
        border-bottom: 1px solid #1e293b;
        padding: 24px 30px;
        background: linear-gradient(to right, #1e293b, #0f172a);
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }
    .modal-header-dark .modal-title {
        font-weight: 800;
        font-size: 20px;
        letter-spacing: -0.5px;
    }
    .modal-body-dark {
        padding: 30px;
    }

    /* Custom Red Close Button */
    .btn-close-red {
        width: 36px;
        height: 36px;
        background-color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        opacity: 0.8;
        transition: all 0.2s;
        border: none;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    .btn-close-red:hover {
        opacity: 1;
        background-color: #dc2626;
        transform: scale(1.1);
    }

    /* Top 5 Cards */
    .top-item-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s;
    }
    .top-item-card:hover {
        border-color: #475569;
        background: #263447;
        transform: translateY(-2px);
    }
    .rank-badge {
        width: 32px; 
        height: 32px; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-weight: 800; 
        font-size: 14px; 
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .sold-text {
        color: #e2e8f0 !important; 
        font-size: 12px;
        font-weight: 600;
        margin-top: 2px;
        display: block;
    }

    /* Full Table */
    .table-dark-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: -10px;
    }
    .table-dark-custom thead th {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        padding: 0 20px 10px 20px;
        border-bottom: 1px solid #334155;
        letter-spacing: 0.5px;
    }
    .table-dark-custom tbody tr {
        background-color: #1e293b;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .table-dark-custom tbody tr:hover {
        background-color: #263447;
        transform: scale(1.005);
    }
    .table-dark-custom tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border: none;
    }
    .table-dark-custom tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    .table-dark-custom tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    .item-name-text {
        color: #ffffff !important;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: -0.2px;
    }
    
    .price-text {
        color: #cbd5e1 !important; 
        font-weight: 600;
        font-size: 14px;
    }
</style>

<div class="pos-dashboard-dark">
    <div class="pos-title mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
            <h1>Manager POS Dashboard</h1>
            <p>Live sales tracking and operational overview for today.</p>
        </div>
        <div>
            <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                <i class="fa-solid fa-circle text-white me-1" style="font-size: 8px;"></i> System Live
            </span>
        </div>
    </div>

    <!-- Row 1: POS Specific Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="neon-card">
                <div class="info">
                    <p class="label">Today's Revenue</p>
                    <h3 class="value">Rs. {{ number_format($todaysRevenue, 0) }}</h3>
                </div>
                <div class="icon-box neon-green">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="neon-card">
                <div class="info">
                    <p class="label">Today's Orders</p>
                    <h3 class="value">{{ number_format($todaysOrders) }}</h3>
                </div>
                <div class="icon-box neon-blue">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="neon-card">
                <div class="info">
                    <p class="label">Pending Orders</p>
                    <h3 class="value">{{ number_format($pendingOrders) }}</h3>
                </div>
                <div class="icon-box neon-orange">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="neon-card">
                <div class="info">
                    <p class="label">Avg. Order Value</p>
                    <h3 class="value">Rs. {{ number_format($avgOrderValue, 0) }}</h3>
                </div>
                <div class="icon-box neon-purple">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Operational Stats -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="neon-card">
                <div class="info">
                    <p class="label">Active Tables</p>
                    <h3 class="value">{{ $activeTables }} <span style="font-size: 16px; color: #94a3b8;">/ {{ $totalTables }} Total</span></h3>
                </div>
                <div class="icon-box neon-pink">
                    <i class="fa-solid fa-chair"></i>
                </div>
            </div>
        </div>
        
        <!-- All Selling Items Card (Clickable) -->
        <div class="col-12 col-md-6">
            <div class="neon-card clickable-card" data-bs-toggle="modal" data-bs-target="#allItemsModal">
                <div class="info d-flex align-items-center gap-3">
                    <div>
                        <p class="label" style="margin-bottom: 2px;">All Selling Items</p>
                        <h3 class="value" style="font-size: 22px; margin: 0;">{{ $uniqueItemsSoldToday }} Items</h3>
                        <span style="font-size: 11px; color: #34d399; font-weight: 600;">
                            Click to view details
                        </span>
                    </div>
                </div>
                <div class="icon-box neon-yellow" style="width: 50px; height: 50px; font-size: 22px; margin-left: auto;">
                    <i class="fa-solid fa-fire"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="dark-graph-card">
                <div class="header">
                    <h4>Hourly Sales Trend (Today)</h4>
                    <span class="tag">Peak Hours</span>
                </div>
                <div class="chart-container">
                    <canvas id="hourlySalesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="dark-graph-card">
                <div class="header">
                    <h4>Sales by Category</h4>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL -->
<!-- ============================================== -->
<div class="modal fade" id="allItemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-content-dark">
            <div class="modal-header modal-header-dark d-flex justify-content-between align-items-center">
                <h5 class="modal-title"><i class="fa-solid fa-chart-line text-warning me-2"></i> Today's Selling Report</h5>
                <button type="button" class="btn-close-red" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body modal-body-dark">
                
                <!-- TOP 5 ITEMS SECTION -->
                <h6 class="text-uppercase fw-bold mb-3" style="font-size: 12px; letter-spacing: 1px; color: #cbd5e1;">Top 5 Items of the Day</h6>
                <div class="row g-3 mb-5">
                    @foreach($top5Items as $index => $item)
                    <div class="col-md-6">
                        <div class="top-item-card">
                            <!-- Rank Badge -->
                            <div class="rank-badge" style="background: {{ $index == 0 ? '#ffd700' : ($index == 1 ? '#c0c0c0' : ($index == 2 ? '#cd7f32' : '#475569')) }}; color: {{ $index < 3 ? '#000' : '#fff' }};">
                                {{ $index + 1 }}
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-grow-1 min-w-0">
                                <!-- Shows Name and Quantity -->
                                <h6 class="mb-1 item-name-text text-truncate">{{ $item->product_name }} <span style="color: #94a3b8; font-weight: 500;">(x{{ $item->total_qty }})</span></h6>
                                <span class="sold-text">Rs. {{ number_format($item->unit_price, 0) }} each</span>
                            </div>
                            
                            <!-- Revenue -->
                            <div class="text-end">
                                <span class="text-success fw-bold" style="font-size: 15px; text-shadow: 0 0 10px rgba(16, 185, 129, 0.3);">Rs. {{ number_format($item->total_revenue, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- FULL TABLE SECTION -->
                <h6 class="text-uppercase fw-bold mb-3" style="font-size: 12px; letter-spacing: 1px; color: #cbd5e1;">Full Breakdown</h6>
                <div class="table-responsive">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th class="text-center">Qty Sold</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSoldItems as $item)
                            <tr>
                                <td>
                                    <!-- Shows Name and Quantity -->
                                    <span class="item-name-text">{{ $item->product_name }} <span style="color: #94a3b8; font-weight: 500;">(x{{ $item->total_qty }})</span></span>
                                </td>
                                <td class="price-text">Rs. {{ number_format($item->unit_price, 0) }}</td>
                                <td class="text-center">
                                    <span class="badge" style="background: #334155; color: #fff; padding: 6px 12px; font-size: 13px; border-radius: 8px; border: 1px solid #475569;">{{ $item->total_qty }}</span>
                                </td>
                                <td class="text-end fw-bold text-success" style="font-size: 15px;">Rs. {{ number_format($item->total_revenue, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No items sold today.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. Hourly Sales Bar Chart ---
        const hourlyCtx = document.getElementById('hourlySalesChart').getContext('2d');
        
        let barGradient = hourlyCtx.createLinearGradient(0, 0, 0, 300);
        barGradient.addColorStop(0, '#ff2d7a');
        barGradient.addColorStop(1, '#ff6b9d');

        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: @json($chartHours),
                datasets: [{
                    label: 'Revenue (PKR)',
                    data: @json($chartSalesData),
                    backgroundColor: barGradient,
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6
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
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'Inter' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter' } }
                    }
                }
            }
        });

        // --- 2. Category Doughnut Chart ---
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryData),
                    backgroundColor: [
                        '#ff2d7a', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#64748b'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            padding: 15,
                            font: { family: 'Inter', size: 12 },
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    });
</script>
@endsection