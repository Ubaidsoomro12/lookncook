<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Staff;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['waiters', 'updater'])->orderByDesc('created_at')->get();
        return view('pos.pages.reservation.index', compact('reservations'));
    }

    public function create()
    {
        $waiters = Staff::where('status', 'Active')->get();
        $categories = Category::where('status', 1)->get(); 
        
        return view('pos.pages.reservation.create', compact('waiters', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'hall_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'total_guests' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'waiters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $res = Reservation::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'hall_name' => $request->hall_name,
                'address' => $request->address,
                'event_date' => $request->event_date,
                'total_guests' => $request->total_guests,
                'food_subtotal' => $request->food_subtotal,
                'hall_payment' => $request->hall_payment ?? 0,
                'stage_decoration' => $request->stage_decoration ?? 0,
                'front_decoration' => $request->front_decoration ?? 0,
                'overall_decoration' => $request->overall_decoration ?? 0,
                'decoration_total' => $request->decoration_total ?? 0,
                'tax_percent' => $request->tax_percent ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount' => $request->discount ?? 0,
                'grand_total' => $request->grand_total,
                'per_head_amount' => $request->per_head_amount,
                'status' => $request->status ?: 'pending', // ADDED
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $this->saveItems($res, $request->items);

            if ($request->waiters) {
                $res->waiters()->sync($request->waiters);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation created successfully.', 'id' => $res->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $reservation = Reservation::with(['items', 'waiters'])->findOrFail($id);
        $waiters = Staff::where('status', 'Active')->get();
        $categories = Category::where('status', 1)->get();
        
        return view('pos.pages.reservation.edit', compact('reservation', 'waiters', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'hall_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'total_guests' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'waiters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $res = Reservation::findOrFail($id);
            
            $res->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'hall_name' => $request->hall_name,
                'address' => $request->address,
                'event_date' => $request->event_date,
                'total_guests' => $request->total_guests,
                'food_subtotal' => $request->food_subtotal,
                'hall_payment' => $request->hall_payment ?? 0,
                'stage_decoration' => $request->stage_decoration ?? 0,
                'front_decoration' => $request->front_decoration ?? 0,
                'overall_decoration' => $request->overall_decoration ?? 0,
                'decoration_total' => $request->decoration_total ?? 0,
                'tax_percent' => $request->tax_percent ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount' => $request->discount ?? 0,
                'grand_total' => $request->grand_total,
                'per_head_amount' => $request->per_head_amount,
                'status' => $request->status ?: $res->status, // ADDED
                'notes' => $request->notes,
                'updated_by' => auth()->id(),
            ]);

            // Delete old items and save new ones
            $res->items()->delete();
            $this->saveItems($res, $request->items);

            // Sync waiters
            $res->waiters()->sync($request->waiters ?? []);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation updated successfully.', 'id' => $res->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function print($id)
    {
        $reservation = Reservation::with(['items', 'waiters', 'creator', 'updater'])->findOrFail($id);
        return view('pos.pages.reservation.print', compact('reservation'));
    }

    public function destroy($id)
    {
        try {
            Reservation::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Reservation deleted.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getFoods($categoryId)
    {
        $foods = Product::where('category_id', $categoryId)->get()->map(function($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'price' => $f->price,
                'image' => $f->image ? asset($f->image) : null
            ];
        });
        return response()->json(['success' => true, 'foods' => $foods]);
    }

    private function saveItems($reservation, $items)
    {
        foreach ($items as $item) {
            ReservationItem::create([
                'reservation_id' => $reservation->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'unit_price' => $item['price'],
                'quantity' => $item['qty'],
                'total_price' => $item['price'] * $item['qty'],
            ]);
        }
    }
}