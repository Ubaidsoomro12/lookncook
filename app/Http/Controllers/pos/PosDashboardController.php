<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\PosTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosDashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // --- 1. Stat Cards Data (Excluding Cancelled Orders) ---
        $todaysRevenue = PosOrder::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
            
        $todaysOrders = PosOrder::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->count();
        
        $activeTables = PosTable::where('status', 'occupied')->count();
        $totalTables = PosTable::where('is_active', true)->count();
        
        $pendingOrders = PosOrder::whereIn('status', ['pending', 'preparing'])->count();
        
        $avgOrderValue = $todaysOrders > 0 ? $todaysRevenue / $todaysOrders : 0;

        // --- 2. All Selling Items Logic (Excluding Cancelled Orders) ---
        $allSoldItems = PosOrderItem::select(
                'product_name', 
                DB::raw('SUM(quantity) as total_qty'), 
                DB::raw('SUM(total_price) as total_revenue'),
                'unit_price'
            )
            ->whereHas('order', function($q) use ($today) {
                $q->whereDate('created_at', $today)
                  ->where('status', '!=', 'cancelled'); // ✅ Exclude cancelled
            })
            ->groupBy('product_name', 'unit_price')
            ->orderByDesc('total_qty')
            ->get();

        // Get the Top 5 items for the modal
        $top5Items = $allSoldItems->take(5);

        // Count of unique items sold today
        $uniqueItemsSoldToday = $allSoldItems->count();

        // --- 3. Hourly Sales Graph Data (Excluding Cancelled Orders) ---
        $hourlySales = PosOrder::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('SUM(total_amount) as total')
        )
        ->whereDate('created_at', $today)
        ->where('status', '!=', 'cancelled') // ✅ Exclude cancelled
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

        $chartHours = [];
        $chartSalesData = [];
        for ($i = 8; $i <= 22; $i++) { 
            $chartHours[] = Carbon::createFromTime($i, 0)->format('h A');
            $sale = $hourlySales->firstWhere('hour', $i);
            $chartSalesData[] = $sale ? (float) $sale->total : 0;
        }

        // --- 4. Category Sales Graph Data (Excluding Cancelled Orders) ---
        $categorySales = PosOrderItem::select('category_id', DB::raw('SUM(total_price) as total'))
            ->whereHas('order', function($q) use ($today) {
                $q->whereDate('created_at', $today)
                  ->where('status', '!=', 'cancelled'); // ✅ Exclude cancelled
            })
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $categoryLabels = [];
        $categoryData = [];
        foreach ($categorySales as $cat) {
            $categoryLabels[] = $cat->category ? $cat->category->name : 'Uncategorized';
            $categoryData[] = (float) $cat->total;
        }

        return view('pos.pos_dashboard', compact(
            'todaysRevenue',
            'todaysOrders',
            'activeTables',
            'totalTables',
            'pendingOrders',
            'avgOrderValue',
            'uniqueItemsSoldToday',
            'top5Items',
            'allSoldItems',
            'chartHours',
            'chartSalesData',
            'categoryLabels',
            'categoryData'
        ));
    }
}