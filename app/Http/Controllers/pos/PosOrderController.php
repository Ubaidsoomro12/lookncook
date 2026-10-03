<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\PosTable;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PosOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $u = auth()->user();
            if (!$u || !in_array($u->role_id, [1, 3])) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /* ============================================================
       INDEX
    ============================================================ */
    public function index(Request $request)
    {
        $query = PosOrder::with(['table', 'waiter', 'items'])
            ->whereIn('status', ['pending', 'preparing', 'served']);

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('waiter_id')) $query->where('waiter_id', $request->waiter_id);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")->orWhere('customer_name', 'like', "%$s%");
            });
        }

        $orders     = $query->latest()->get();
        $tables     = PosTable::orderBy('table_number')->get();
        $waiters    = User::where('role_id', 4)->orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name', 'image']);

        $stats = [
            'pending'   => PosOrder::where('status', 'pending')->count(),
            'preparing' => PosOrder::where('status', 'preparing')->count(),
            'served'    => PosOrder::where('status', 'served')->count(),
            'today'     => PosOrder::whereDate('created_at', today())->count(),
        ];

        return view('pos.pages.orders.index', compact('orders', 'tables', 'waiters', 'categories', 'stats'));
    }

    /* ============================================================
       CREATE
    ============================================================ */
    public function create()
    {
        $tables  = PosTable::orderBy('table_number')->get();
        $waiters = User::where('role_id', 4)->orderBy('name')->get(['id', 'name']);
        return view('pos.pages.orders.create', compact('tables', 'waiters'));
    }

    /* ============================================================
       STORE
    ============================================================ */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'pos_table_id'  => 'required|exists:pos_tables,id',
            'waiter_id'     => 'required|exists:users,id',
            'customer_name' => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
        ]);

        if ($v->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $v->errors()], 422);
            }
            return back()->withErrors($v)->withInput();
        }

        DB::beginTransaction();
        try {
            $table = PosTable::findOrFail($request->pos_table_id);
            $order = PosOrder::create([
                'order_number'  => PosOrder::generateOrderNumber(),
                'pos_table_id'  => $table->id,
                'waiter_id'     => $request->waiter_id,
                'customer_name' => $request->customer_name,
                'notes'         => $request->notes,
                'status'        => 'pending',
                'created_by'    => auth()->id(),
            ]);
            $table->update(['status' => 'occupied']);
            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Order created', 'order' => $this->orderPayload($order, true)]);
            }
            return redirect()->route('pos.orders.edit', $order->id)->with('success', 'Order created — now add items.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /* ============================================================
       EDIT
    ============================================================ */
    public function edit($id)
    {
        $order = PosOrder::with(['items.category', 'items.product', 'table', 'waiter'])->findOrFail($id);
        $tables     = PosTable::orderBy('table_number')->get();
        $waiters    = User::where('role_id', 4)->orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name', 'image']);
        return view('pos.pages.orders.edit', compact('order', 'tables', 'waiters', 'categories'));
    }

    /* ============================================================
       SHOW
    ============================================================ */
    public function show($id)
    {
        try {
            $order = PosOrder::with(['table', 'waiter', 'items.category', 'items.product'])->findOrFail($id);
            return response()->json(['success' => true, 'order' => $this->orderPayload($order, true)]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       UPDATE
    ============================================================ */
    public function update(Request $request, $id)
    {
        $order = PosOrder::findOrFail($id);
        $v = Validator::make($request->all(), [
            'waiter_id'     => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
            'status'        => 'nullable|in:pending,preparing,served,completed,cancelled',
        ]);

        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $order->update($request->only(['waiter_id', 'customer_name', 'notes', 'status']));

        if (in_array($order->status, ['completed', 'cancelled']) && $order->table) {
            $order->table->update(['status' => 'available']);
        }

        return response()->json([
            'success' => true,
            'order'   => $this->orderPayload($order->fresh(['items.category', 'items.product', 'table', 'waiter']), true),
        ]);
    }

    /* ============================================================
       ADD ITEM
    ============================================================ */
    public function addItem(Request $request, $id)
    {
        $order = PosOrder::findOrFail($id);
        $v = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:999',
            'notes'      => 'nullable|string|max:255',
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $product = Product::findOrFail($request->product_id);
        $qty     = (int) $request->quantity;
        $price   = (float) $product->price;

        $existing = $order->items()->where('product_id', $product->id)->whereNull('notes')->first();

        if ($existing && !$request->notes) {
            $existing->update([
                'quantity'    => $existing->quantity + $qty,
                'total_price' => ($existing->quantity + $qty) * $price,
            ]);
        } else {
            PosOrderItem::create([
                'pos_order_id' => $order->id,
                'product_id'   => $product->id,
                'category_id'  => $product->category_id ?? null,
                'product_name' => $product->name,
                'quantity'     => $qty,
                'unit_price'   => $price,
                'total_price'  => $qty * $price,
                'notes'        => $request->notes,
            ]);
        }
        $order->recalculateTotals();
        return response()->json(['success' => true, 'order' => $this->orderPayload($order->fresh(['items.category', 'items.product', 'table', 'waiter']), true)]);
    }

    /* ============================================================
       UPDATE ITEM QTY
    ============================================================ */
    public function updateItem(Request $request, $orderId, $itemId)
    {
        $order = PosOrder::findOrFail($orderId);
        $item  = $order->items()->findOrFail($itemId);
        $v = Validator::make($request->all(), ['quantity' => 'required|integer|min:1|max:999']);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $item->update(['quantity' => $request->quantity, 'total_price' => $request->quantity * $item->unit_price]);
        $order->recalculateTotals();
        return response()->json(['success' => true, 'order' => $this->orderPayload($order->fresh(['items.category', 'items.product', 'table', 'waiter']), true)]);
    }

    /* ============================================================
       REMOVE ITEM
    ============================================================ */
    public function removeItem($orderId, $itemId)
    {
        $order = PosOrder::findOrFail($orderId);
        $item  = $order->items()->findOrFail($itemId);
        $item->delete();
        $order->recalculateTotals();
        return response()->json(['success' => true, 'order' => $this->orderPayload($order->fresh(['items.category', 'items.product', 'table', 'waiter']), true)]);
    }

    /* ============================================================
       APPLY CHARGES
    ============================================================ */
    public function applyCharges(Request $request, $id)
    {
        $order = PosOrder::findOrFail($id);
        $v = Validator::make($request->all(), [
            'tax'      => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $order->tax      = $request->tax ?? 0;
        $order->discount = $request->discount ?? 0;
        $order->save();
        $order->recalculateTotals();

        return response()->json([
            'success' => true,
            'order'   => $this->orderPayload($order->fresh(['items.category', 'items.product', 'table', 'waiter']), true),
        ]);
    }

    /* ============================================================
       DESTROY
    ============================================================ */
    public function destroy($id)
    {
        $order = PosOrder::findOrFail($id);
        if ($order->table) $order->table->update(['status' => 'available']);
        $order->delete();
        return response()->json(['success' => true, 'message' => 'Order deleted']);
    }

    /* ============================================================
       FOODS BY CATEGORY
    ============================================================ */
    public function foodsByCategory($categoryId)
    {
        $foods = Product::where('category_id', $categoryId)->orderBy('name')->get(['id', 'name', 'price', 'image']);
        return response()->json([
            'success' => true,
            'foods'   => $foods->map(fn ($f) => [
                'id'    => $f->id,
                'name'  => $f->name,
                'price' => (float) $f->price,
                'image' => $f->image ? asset($f->image) : null,
            ]),
        ]);
    }

    /* ============================================================
       PAYLOAD HELPER
    ============================================================ */
    private function orderPayload(PosOrder $order, bool $withItems = false): array
    {
        $data = [
            'id'              => $order->id,
            'order_number'    => $order->order_number,
            'status'          => $order->status,
            'customer_name'   => $order->customer_name,
            'notes'           => $order->notes,
            'subtotal'        => (float) $order->subtotal,
            'tax'             => (float) $order->tax,
            'tax_amount'      => (float) $order->tax_amount,
            'discount'        => (float) $order->discount,
            'discount_amount' => (float) $order->discount_amount,
            'total_amount'    => (float) $order->total_amount,
            'waiter_id'       => $order->waiter_id,
            'waiter_name'     => $order->waiter?->name,
            'table_id'        => $order->pos_table_id,
            'table_number'    => $order->table?->table_number,
            'table_name'      => $order->table?->table_name,
            'created_at'      => $order->created_at?->format('d M Y, h:i A'),
        ];

        if ($withItems) {
            $data['items'] = $order->items->map(fn ($i) => [
                'id'           => $i->id,
                'product_id'   => $i->product_id,
                'product_name' => $i->product_name,
                'category'     => $i->relationLoaded('category') ? $i->category?->name : null,
                'quantity'     => $i->quantity,
                'unit_price'   => (float) $i->unit_price,
                'total_price'  => (float) $i->total_price,
                'notes'        => $i->notes,
            ])->values();
        }
        return $data;
    }

    /* ============================================================
       PRINT RECEIPT — opens a new window with 2 receipts
       - Customer copy on page 1
       - Department copy on page 2
       Browser's Ctrl+P handles the Windows Print dialog.
    ============================================================ */
    public function printReceipt($id)
    {
        $order = PosOrder::with(['items.category', 'table', 'waiter'])->findOrFail($id);
        return view('pos.pages.orders.receipt_print', compact('order'));
    }

    /* ============================================================
       RECEIPT DATA — Single PDF with BOTH receipts
       - Page 1: Customer Receipt
       - Page 2: Department Receipt
       - Saves to D:\lookncook recipts
       - Returns base64 JSON (bypasses IDM)
    ============================================================ */
    public function receiptData(Request $request, $id)
    {
        try {
            $order = PosOrder::with(['items.category', 'table', 'waiter'])->findOrFail($id);

            try {
                // Load the combined view (Customer + Department on 2 pages)
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pos.pages.orders.receipt_both', [
                    'order' => $order,
                ])->setPaper('a4', 'portrait');

                $filename   = 'receipt-' . $order->order_number . '.pdf';
                $pdfContent = $pdf->output();
            } catch (\Throwable $e) {
                Log::error('PDF Generation Failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error'   => 'PDF Generation Failed: ' . $e->getMessage(),
                ], 500);
            }

            // Save to D:\lookncook recipts
            $folder    = 'D:\\lookncook recipts';
            $fullPath  = $folder . DIRECTORY_SEPARATOR . $filename;
            $saved     = false;
            $error     = null;

            try {
                if (!is_dir($folder)) @mkdir($folder, 0777, true);
                if (!is_dir($folder)) {
                    $error = "Failed to create directory: {$folder}";
                } else {
                    $bytes = @file_put_contents($fullPath, $pdfContent);
                    if ($bytes === false) {
                        $err = error_get_last();
                        $error = $err['message'] ?? 'file_put_contents returned false';
                    } elseif ($bytes === 0) {
                        $error = 'Zero bytes written';
                    } elseif ($bytes !== strlen($pdfContent)) {
                        $error = "Partial write: {$bytes} of " . strlen($pdfContent) . " bytes";
                    } else {
                        $saved = true;
                    }
                }
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }

            return response()->json([
                'success'  => true,
                'filename' => $filename,
                'mime'     => 'application/pdf',
                'base64'   => base64_encode($pdfContent),
                'saved'    => $saved,
                'saved_to' => $saved ? $fullPath : null,
                'error'    => $error,
            ]);
        } catch (\Throwable $e) {
            Log::error('Receipt Data Fatal Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Fatal Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}