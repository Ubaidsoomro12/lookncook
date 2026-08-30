<?php

namespace App\Http\Controllers\Backend;

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
     * Display a listing of orders (Orders admin section).
     */
    public function index()
    {
        $orders = Order::with(['user', 'paymentMethod', 'rider'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Pass $paymentMethods to the view so filtering works
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Active riders for the "Assign Rider" popup dropdown
        $riders = Rider::where('is_active', 1)->orderBy('name')->get();

        return view('admin.pages.payments.index', compact('orders', 'paymentMethods', 'riders'));
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
                  ->orWhere('customer_phone', 'like', "%{$query}%") // Added phone search
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
            // Expose phone and screenshot for the AJAX table render
            $order->customer_phone = $order->customer_phone;
            $order->payment_screenshot = $order->payment_screenshot;
            // Expose rider assignment info for the AJAX table render
            $order->rider_id = $order->rider_assigned;
            $order->rider_name = $order->rider?->name;
            $order->rider_image = $order->rider?->image_url;
            $order->estimated_time = $order->estimated_time;
            $order->status = $order->status;
            return $order;
        });

        return response()->json(['orders' => $orders]);
    }
}