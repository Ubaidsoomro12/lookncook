<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Order;   
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Sample orders data for UI demonstration
        $orders = $this->getSampleOrders();
        
        return view('pages.orders.index', compact('orders'));
    }

    private function getSampleOrders()
    {
        $orders = [];
        
        // Order 1
        $orders[] = (object) [
            'id' => 1,
            'order_number' => 'ORD-1001',
            'total_amount' => 1250.00,
            'status' => 'pending',
            'address' => 'House #12, Street 5, Gulshan-e-Iqbal, Karachi',
            'created_at' => now()->subHours(2),
            'items' => collect([
                (object) ['product' => (object) ['name' => 'Chicken Biryani'], 'quantity' => 2],
                (object) ['product' => (object) ['name' => 'Garlic Naan'], 'quantity' => 1],
                (object) ['product' => (object) ['name' => 'Cold Drink'], 'quantity' => 3],
            ]),
            'rider_name' => 'Ahmed Khan',
            'vehicle_no' => 'ABC-123',
            'rider_contact' => '+92 300 1234567'
        ];

        // Order 2
        $orders[] = (object) [
            'id' => 2,
            'order_number' => 'ORD-1002',
            'total_amount' => 850.00,
            'status' => 'processing',
            'address' => 'Flat #3, Block B, North Nazimabad, Karachi',
            'created_at' => now()->subHours(5),
            'items' => collect([
                (object) ['product' => (object) ['name' => 'Chicken Karahi'], 'quantity' => 1],
                (object) ['product' => (object) ['name' => 'Roti'], 'quantity' => 4],
                (object) ['product' => (object) ['name' => 'Raita'], 'quantity' => 1],
            ]),
            'rider_name' => 'Saima Ali',
            'vehicle_no' => 'XYZ-789',
            'rider_contact' => '+92 321 9876543'
        ];

        // Order 3
        $orders[] = (object) [
            'id' => 3,
            'order_number' => 'ORD-1003',
            'total_amount' => 2100.00,
            'status' => 'delivered',
            'address' => 'House #45, Main Road, DHA Phase 6, Karachi',
            'created_at' => now()->subHours(8),
            'items' => collect([
                (object) ['product' => (object) ['name' => 'Chicken Tikka'], 'quantity' => 3],
                (object) ['product' => (object) ['name' => 'Fried Rice'], 'quantity' => 2],
                (object) ['product' => (object) ['name' => 'Mint Chutney'], 'quantity' => 1],
            ]),
            'rider_name' => 'Usman Malik',
            'vehicle_no' => 'DEF-456',
            'rider_contact' => '+92 333 4567890'
        ];

        return collect($orders);
    }
}