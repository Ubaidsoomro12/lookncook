<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors([
                    'email' => 'You do not have administrative privileges to access this area.'
                ]);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of orders with pagination and calculations.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $orders = Order::with(['user', 'paymentMethod', 'rider'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $allOrders = Order::all();
        $totalOrders = $allOrders->count();
        $totalAmount = $allOrders->sum('total_amount');
        $totalCustomers = $allOrders->unique('customer_email')->count();
        $totalApproved = $allOrders->where('payment_status', 'approved')->count();
        $totalPending = $allOrders->where('payment_status', 'pending')->count();
        $totalFailed = $allOrders->where('payment_status', 'failed')->count();
        $totalCompleted = $allOrders->where('payment_status', 'completed')->count();
        $totalRiderAssigned = $allOrders->whereNotNull('rider_assigned')->count();

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $riders = Rider::where('is_active', 1)->orderBy('name')->get();

        return view('admin.pages.payments.index', compact(
            'orders', 
            'paymentMethods', 
            'riders',
            'totalOrders',
            'totalAmount',
            'totalCustomers',
            'totalApproved',
            'totalPending',
            'totalFailed',
            'totalCompleted',
            'totalRiderAssigned'
        ));
    }

    /**
     * Export orders to CSV with restaurant-style formatting.
     * Saves to public/orders_records folder
     */
    public function export(Request $request)
    {
        try {
            $filterType = $request->get('filter_type', 'all');
            $filterDate = $request->get('filter_date', date('Y-m-d'));
            
            \Log::info('Export request received', ['filter_type' => $filterType, 'filter_date' => $filterDate]);
            
            $query = Order::with(['user', 'paymentMethod', 'rider']);
            
            // Apply date filter
            $filterLabel = 'All Records';
            $filterPeriod = 'All Time';
            
            if ($filterType === 'day') {
                $query->whereDate('created_at', $filterDate);
                $filterLabel = date('d M Y', strtotime($filterDate));
                $filterPeriod = 'Daily Report - ' . date('l, d M Y', strtotime($filterDate));
            } elseif ($filterType === 'month') {
                $year = date('Y', strtotime($filterDate));
                $month = date('m', strtotime($filterDate));
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
                $filterLabel = date('F Y', strtotime($filterDate));
                $filterPeriod = 'Monthly Report - ' . date('F Y', strtotime($filterDate));
            } elseif ($filterType === 'year') {
                $query->whereYear('created_at', $filterDate);
                $filterLabel = $filterDate;
                $filterPeriod = 'Yearly Report - ' . $filterDate;
            }
            
            $orders = $query->orderBy('created_at', 'desc')->get();
            
            \Log::info('Orders found', ['count' => $orders->count()]);
            
            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found for the selected period.'
                ]);
            }

            // Calculate statistics
            $totalAmount = $orders->sum('total_amount');
            $totalOrders = $orders->count();
            $totalCustomers = $orders->unique('customer_email')->count();
            $totalApproved = $orders->where('payment_status', 'approved')->count();
            $totalPending = $orders->where('payment_status', 'pending')->count();
            $totalFailed = $orders->where('payment_status', 'failed')->count();
            $totalCompleted = $orders->where('payment_status', 'completed')->count();
            $totalRiderAssigned = $orders->whereNotNull('rider_assigned')->count();
            $totalItems = $orders->sum(function($order) {
                return $order->items->sum('quantity');
            });

            // Create CSV file
            $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
            $filePath = public_path('orders_records/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(public_path('orders_records'))) {
                mkdir(public_path('orders_records'), 0777, true);
            }
            
            $handle = fopen($filePath, 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");
            
            // ============================================================
            // 1. RESTAURANT HEADER
            // ============================================================
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, ['"                    LOOK N COOK RESTAURANT"']);
            fputcsv($handle, ['"                       ORDERS REPORT"']);
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, []);
            fputcsv($handle, ['"Report Type:", "' . $filterPeriod . '"']);
            fputcsv($handle, ['"Generated On:", "' . date('l, d M Y h:i A') . '"']);
            fputcsv($handle, ['"Generated By:", "' . auth()->user()->name . '"']);
            fputcsv($handle, []);
            
            // ============================================================
            // 2. EXECUTIVE SUMMARY
            // ============================================================
            fputcsv($handle, ['"------------------- EXECUTIVE SUMMARY -------------------"']);
            fputcsv($handle, []);
            fputcsv($handle, ['"Metric", "Value"']);
            fputcsv($handle, ['"Total Orders", "' . $totalOrders . '"']);
            fputcsv($handle, ['"Total Revenue", "Rs. ' . number_format($totalAmount, 2) . '"']);
            fputcsv($handle, ['"Total Customers", "' . $totalCustomers . '"']);
            fputcsv($handle, ['"Total Items Sold", "' . $totalItems . '"']);
            fputcsv($handle, ['"Average Order Value", "Rs. ' . number_format($totalOrders > 0 ? $totalAmount / $totalOrders : 0, 2) . '"']);
            fputcsv($handle, []);
            
            // ============================================================
            // 3. PAYMENT STATUS BREAKDOWN
            // ============================================================
            fputcsv($handle, ['"------------------- PAYMENT STATUS BREAKDOWN -------------------"']);
            fputcsv($handle, []);
            fputcsv($handle, ['"Status", "Count", "Percentage"']);
            
            $statusData = [
                ['Approved', $totalApproved],
                ['Pending', $totalPending],
                ['Failed', $totalFailed],
                ['Completed', $totalCompleted],
            ];
            
            foreach ($statusData as $status) {
                $percentage = $totalOrders > 0 ? round(($status[1] / $totalOrders) * 100, 1) : 0;
                fputcsv($handle, ['"' . $status[0] . '"', '"' . $status[1] . '"', '"' . $percentage . '%"']);
            }
            fputcsv($handle, []);
            
            // ============================================================
            // 4. RIDER PERFORMANCE
            // ============================================================
            if ($totalRiderAssigned > 0) {
                fputcsv($handle, ['"------------------- RIDER PERFORMANCE -------------------"']);
                fputcsv($handle, []);
                fputcsv($handle, ['"Rider Name", "Orders Assigned", "Status"']);
                
                $ridersPerformance = $orders->whereNotNull('rider_assigned')->groupBy('rider_assigned')->map(function($group) {
                    return [
                        'name' => $group->first()->rider?->name ?? 'Unknown',
                        'count' => $group->count(),
                        'status' => $group->first()->status ?? 'review'
                    ];
                });
                
                foreach ($ridersPerformance as $rider) {
                    fputcsv($handle, ['"' . $rider['name'] . '"', '"' . $rider['count'] . '"', '"' . ucfirst($rider['status']) . '"']);
                }
                fputcsv($handle, []);
            }
            
            // ============================================================
            // 5. ORDER DETAILS
            // ============================================================
            fputcsv($handle, ['"==================== ORDER DETAILS ===================="']);
            fputcsv($handle, []);
            
            // Main headers
            $headers = [
                'S.No',
                'Order #',
                'Customer Name',
                'Customer Email',
                'Phone',
                'Total (Rs)',
                'Payment Method',
                'Payment Status',
                'Rider Name',
                'Rider Status',
                'Est. Time',
                'Delivery Address',
                'City',
                'Transaction Ref',
                'Order Date',
                'Items Count'
            ];
            
            fputcsv($handle, $headers);
            fputcsv($handle, [
                '---',
                '---------',
                '-------------',
                '--------------',
                '-----',
                '---------',
                '--------------',
                '--------------',
                '----------',
                '------------',
                '---------',
                '---------------',
                '----',
                '--------------',
                '----------',
                '------------'
            ]);
            
            // Write data
            $rowNumber = 1;
            foreach ($orders as $order) {
                $itemsCount = $order->items->sum('quantity');
                fputcsv($handle, [
                    $rowNumber,
                    '#' . $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone ?? '—',
                    number_format($order->total_amount, 2),
                    $order->paymentMethod?->name ?? '—',
                    ucfirst($order->payment_status),
                    $order->rider?->name ?? 'Unassigned',
                    ucfirst($order->status ?? 'review'),
                    $order->estimated_time ?? '—',
                    $order->delivery_address ?? '—',
                    $order->city ?? '—',
                    $order->transaction_reference ?? '—',
                    $order->created_at->format('d M Y, h:i A'),
                    $itemsCount
                ]);
                $rowNumber++;
            }
            
            fputcsv($handle, []);
            
            // ============================================================
            // 6. FOOTER WITH TOTALS
            // ============================================================
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, ['"                          TOTALS"']);
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, []);
            fputcsv($handle, ['"Total Orders:", "' . $totalOrders . '"']);
            fputcsv($handle, ['"Total Revenue:", "Rs. ' . number_format($totalAmount, 2) . '"']);
            fputcsv($handle, ['"Total Customers:", "' . $totalCustomers . '"']);
            fputcsv($handle, ['"Total Items Sold:", "' . $totalItems . '"']);
            fputcsv($handle, ['"Average Order Value:", "Rs. ' . number_format($totalOrders > 0 ? $totalAmount / $totalOrders : 0, 2) . '"']);
            fputcsv($handle, []);
            
            // ============================================================
            // 7. PAYMENT METHOD BREAKDOWN
            // ============================================================
            fputcsv($handle, ['"------------------- PAYMENT METHODS -------------------"']);
            fputcsv($handle, []);
            fputcsv($handle, ['"Payment Method", "Count", "Amount (Rs)"']);
            
            $paymentMethodsBreakdown = $orders->groupBy('payment_method_id')->map(function($group) {
                return [
                    'name' => $group->first()->paymentMethod?->name ?? 'Unknown',
                    'count' => $group->count(),
                    'amount' => $group->sum('total_amount')
                ];
            });
            
            foreach ($paymentMethodsBreakdown as $method) {
                fputcsv($handle, ['"' . $method['name'] . '"', '"' . $method['count'] . '"', '"' . number_format($method['amount'], 2) . '"']);
            }
            fputcsv($handle, []);
            
            // ============================================================
            // 8. FOOTER
            // ============================================================
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, ['"  Report generated by Look N Cook Restaurant Management System"']);
            fputcsv($handle, ['"  Generated on: ' . date('l, d M Y h:i A') . '"']);
            fputcsv($handle, ['"  Total Records: ' . $totalOrders . ' orders"']);
            fputcsv($handle, ['"============================================================="']);
            fputcsv($handle, ['"  Thank you for choosing Look N Cook!"']);
            fputcsv($handle, ['"  For support: support@lookncook.com"']);
            fputcsv($handle, ['"============================================================="']);
            
            fclose($handle);
            
            // Get order IDs for deletion
            $orderIds = $orders->pluck('id')->toArray();
            
            // Delete the exported records from database
            Order::whereIn('id', $orderIds)->delete();

            \Log::info('Export completed', ['count' => $orders->count(), 'file' => $filename]);

            return response()->json([
                'success' => true,
                'message' => 'Export completed successfully. ' . $orders->count() . ' orders exported and deleted.',
                'file' => asset('orders_records/' . $filename),
                'filename' => $filename,
                'count' => $orders->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Export error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified order details (AJAX modal).
     */
    public function show(Order $order)
    {
        $order->load(['user', 'paymentMethod', 'items.product', 'rider']);
        $html = View::make('admin.pages.payments._details_modal', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * Approve a pending payment.
     */
    public function approve(Order $order)
    {
        if (!in_array($order->payment_status, ['pending', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending or failed payments can be approved.'
            ], 422);
        }

        $order->payment_status = 'approved';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment approved successfully.'
        ]);
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.'
        ]);
    }

    /**
     * Search orders via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $orders = Order::with(['user', 'paymentMethod', 'rider'])
            ->where(function ($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('customer_name', 'like', "%{$query}%")
                  ->orWhere('customer_email', 'like', "%{$query}%")
                  ->orWhere('customer_phone', 'like', "%{$query}%")
                  ->orWhere('transaction_reference', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $orders->transform(function ($order) {
            $order->payment_method_name = $order->paymentMethod?->name;
            $order->show_url = route('admin.payments.show', $order);
            $order->approve_url = route('admin.payments.approve', $order);
            $order->delete_url = route('admin.payments.destroy', $order);
            $order->assign_url = route('admin.orders.assign', $order);
            $order->customer_phone = $order->customer_phone;
            $order->payment_screenshot = $order->payment_screenshot;
            $order->rider_id = $order->rider_assigned;
            $order->rider_name = $order->rider?->name;
            $order->rider_image = $order->rider?->image_url;
            $order->estimated_time = $order->estimated_time;
            $order->status = $order->status;
            return $order;
        });

        return response()->json(['orders' => $orders]);
    }

    /**
     * Get statistics for dashboard.
     */
    public function stats()
    {
        $today = date('Y-m-d');
        $month = date('Y-m');
        $year = date('Y');

        $stats = [
            'today' => [
                'orders' => Order::whereDate('created_at', $today)->count(),
                'amount' => Order::whereDate('created_at', $today)->sum('total_amount'),
            ],
            'month' => [
                'orders' => Order::whereYear('created_at', date('Y', strtotime($month)))
                                 ->whereMonth('created_at', date('m', strtotime($month)))
                                 ->count(),
                'amount' => Order::whereYear('created_at', date('Y', strtotime($month)))
                                 ->whereMonth('created_at', date('m', strtotime($month)))
                                 ->sum('total_amount'),
            ],
            'year' => [
                'orders' => Order::whereYear('created_at', $year)->count(),
                'amount' => Order::whereYear('created_at', $year)->sum('total_amount'),
            ],
            'total' => [
                'orders' => Order::count(),
                'amount' => Order::sum('total_amount'),
            ]
        ];

        return response()->json($stats);
    }
}