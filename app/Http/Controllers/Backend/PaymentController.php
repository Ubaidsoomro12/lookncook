<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $orders = Order::with(['user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Corrected view path: admin/pages/payments/index.blade.php
        return view('admin.pages.payments.index', compact('orders'));
    }

    /**
     * Show the specified payment details (AJAX modal).
     */
    public function show(Order $order)
    {
        $order->load(['user', 'paymentMethod', 'items.product']);
        // Corrected view path: admin/pages/payments/_details_modal.blade.php
        $html = View::make('admin.pages.payments._details_modal', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * Approve a pending payment.
     */
    public function approve(Order $order)
    {
        // Only allow pending or failed payments to be approved
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
     * Remove the specified payment.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.'
        ]);
    }

    /**
     * Search payments via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $orders = Order::with(['user', 'paymentMethod'])
            ->where(function ($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('customer_name', 'like', "%{$query}%")
                  ->orWhere('customer_email', 'like', "%{$query}%")
                  ->orWhere('transaction_reference', 'like', "%{$query}%")
                  ->orWhere('stripe_payment_intent_id', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $orders->transform(function ($order) {
            $order->payment_method_name = $order->paymentMethod?->name;
            $order->show_url = route('admin.payments.show', $order);
            $order->approve_url = route('admin.payments.approve', $order);
            $order->delete_url = route('admin.payments.destroy', $order);
            return $order;
        });

        return response()->json(['orders' => $orders]);
    }
}