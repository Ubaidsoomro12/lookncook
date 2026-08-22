@extends('pos.layouts.pos_master')
@section('title', 'Point of Sales Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">POS Dashboard</h1>
    <p class="text-sm text-gray-500 mt-0.5">Real-time overview of your point of sales performance metrics.</p>
</div>

<!-- Dynamic Grid Performance Analytics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Revenue -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Revenue</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rs. 0.00</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 text-xl shadow-inner">
            <i class="fa-solid fa-wallet"></i>
        </div>
    </div>

    <!-- Profit -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Profit</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rs. 0.00</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 text-xl shadow-inner">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>

    <!-- Orders -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orders</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">6</h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 text-xl shadow-inner">
            <i class="fa-solid fa-box-open"></i>
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reviews</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">21</h3>
        </div>
        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 text-xl shadow-inner">
            <i class="fa-solid fa-star"></i>
        </div>
    </div>
</div>

<!-- Row Array Block Component (Contacts, Riders, Users) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Contacts -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contacts</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">59</h3>
        </div>
        <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center text-pink-500 text-xl shadow-inner">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>

    <!-- Riders -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Riders</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">3</h3>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500 text-xl shadow-inner">
            <i class="fa-solid fa-motorcycle"></i>
        </div>
    </div>

    <!-- Users -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between sm:col-span-2 lg:col-span-1">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Users</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">1,253</h3>
        </div>
        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-xl shadow-inner">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
</div>

<!-- Analytics Graphs -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Sales Graph -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-gray-900">Sales Overview Analytics</h4>
            <span class="text-xs px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-medium">Live Active Tracking</span>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Orders Breakdown -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-gray-900">Orders Lifecycle Breakdown</h4>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
                    tension: 0.4
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
</script>
@endsection