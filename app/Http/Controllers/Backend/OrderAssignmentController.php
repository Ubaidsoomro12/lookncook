<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges.']);
            }
            return $next($request);
        });
    }

    public function assign(Request $request, Order $order)
    {
        Log::info('Assign request received for order ' . $order->id, $request->all());

        if ($order->payment_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'This order must be approved before a rider can be assigned.',
            ], 422);
        }

        $validated = $request->validate([
            'rider_id'       => 'required|exists:riders,id',
            'estimated_time' => 'required|string|max:50',
            'status'         => 'required|in:review,preparing,completed,delivered',
        ]);

        try {
            // Force the write (avoids any mass-assignment / dirty-check issues)
            $order->rider_assigned = $validated['rider_id'];
            $order->estimated_time = $validated['estimated_time'];
            $order->status         = $validated['status'];
            $order->save();

            // Make sure we have the latest data from DB
            $order->refresh();
            $order->load('rider');

            Log::info('Order updated successfully', [
                'order_id'       => $order->id,
                'rider_id'       => $order->rider_assigned,
                'estimated_time' => $order->estimated_time,
                'status'         => $order->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update order', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rider assigned successfully.',
            'order'   => [
                'id'             => $order->id,
                'rider_id'       => $order->rider_assigned,
                'rider_name'     => $order->rider?->name,
                'rider_image'    => $order->rider?->image_url,
                'vehicle_type'   => $order->rider?->vehicle_type,
                'estimated_time' => $order->estimated_time,
                'status'         => $order->status,
            ],
        ]);
    }
}