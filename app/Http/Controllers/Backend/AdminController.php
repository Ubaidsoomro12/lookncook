<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Rider;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // --- 1. Stat Cards Data ---
        // Assuming Order has a status, we calculate revenue from completed/delivered orders.
        $totalRevenue = Order::whereIn('status', ['completed', 'delivered', 'Delivered'])->sum('total_amount');
        
        // Profit calculation (Placeholder logic: e.g., 20% of revenue if no cost field exists)
        // If you have cost fields, replace this. For now, we'll do 20% of revenue as an example.
        $totalProfit = $totalRevenue * 0.20; 

        $totalOrders = Order::count();
        $totalReviews = Review::count();
        $totalContacts = Contact::count();
        $totalRiders = Rider::count();
        $totalUsers = User::where('role_id', 2)->count(); // Assuming role_id 2 is Customer/User

        // --- 2. Graph Data: Sales Overview (Last 7 Days) ---
        $salesLast7Days = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $chartDates = [];
        $chartSales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::now()->subDays($i)->format('M d');
            
            $daySale = $salesLast7Days->firstWhere('date', $date);
            $chartSales[] = $daySale ? (float) $daySale->total : 0;
        }

        // --- 3. Graph Data: Order Lifecycle Breakdown ---
        $orderStatuses = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $statusLabels = [];
        $statusCounts = [];

        foreach ($orderStatuses as $status) {
            $statusLabels[] = ucfirst(str_replace('_', ' ', $status->status));
            $statusCounts[] = $status->count;
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalProfit',
            'totalOrders',
            'totalReviews',
            'totalContacts',
            'totalRiders',
            'totalUsers',
            'chartDates',
            'chartSales',
            'statusLabels',
            'statusCounts'
        ));
    }
}