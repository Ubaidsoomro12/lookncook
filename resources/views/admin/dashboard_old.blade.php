@extends('admin.layouts.master')

@section('admin_content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Admin Dashboard</h1>
    <p class="text-sm text-gray-500 mt-0.5">Central overview of your platform's performance metrics context profile.</p>
</div>

<!-- Dynamic Grid Performance Analytics Section Counters Array Components Layout View (Same as result.jpg grid array block structures) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Revenue Display Metric Counter Element -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Revenue</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rs. 0.00</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 text-xl shadow-inner">
            <i class="fa-solid fa-wallet"></i>
        </div>
    </div>

    <!-- Profit Display Metric Counter Element -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Profit</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rs. 0.00</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 text-xl shadow-inner">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>

    <!-- Orders Queue Display Metric Counter Element -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orders</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">6</h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 text-xl shadow-inner">
            <i class="fa-solid fa-box-open"></i>
        </div>
    </div>

    <!-- Reviews Counter Display Element -->
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

<!-- Row Array Block Component 2 Layer (Contacts, Riders, Users) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Contacts Counter Display Element -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contacts</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">59</h3>
        </div>
        <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center text-pink-500 text-xl shadow-inner">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>

    <!-- Riders Display Metric Element -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Riders</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">3</h3>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500 text-xl shadow-inner">
            <i class="fa-solid fa-motorcycle"></i>
        </div>
    </div>

    <!-- Users Counter Display Element -->
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

<!-- Analytics Graphs Data Layout Visual Section Mockup Array Content Blocks Area -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Hand Side Graph Panel Column Wrapper Line Matrix Box -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-gray-900">Sales Overview Analytics</h4>
            <span class="text-xs px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-medium">Live Active Tracking Data Matrix</span>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400">
            [ Dynamic Graphical Data Line Engine Map Component Wrapper Frame Area ]
        </div>
    </div>

    <!-- Right Hand Side Pie Donut Data Structure Layout Metric Panel Column Wrapper -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-gray-900">Orders Lifecycle Breakdown</h4>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400">
            [ Doughnut Segment Distribution Lifecycle Viewport Area ]
        </div>
    </div>

</div>
@endsection