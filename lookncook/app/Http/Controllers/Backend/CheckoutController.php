<?php
// FILE: app/Http/Controllers/Backend/CheckoutController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.payment_page', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        // ================================================================
        // 1. Validate that a real, existing payment method was submitted
        //    BEFORE we try to load it (avoids a raw 404 on bad input).
        // ================================================================
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        if (!$paymentMethod->is_active) {
            return back()->withErrors(['payment_method_id' => 'Selected payment method is not available.'])->withInput();
        }

        // ================================================================
        // 🛡️ ROBUST COD DETECTION
        // If an admin mis-set the "type" column for a Cash-on-Delivery
        // method (e.g. left it as "bank" or "mobile_wallet" by mistake),
        // detect it from the name/slug instead so COD never demands a
        // screenshot. This checks both "cash" and "cod" keywords, and
        // checks the slug too (not just the display name).
        // ================================================================
        $paymentType = $paymentMethod->type;

        $normalizedName = strtolower(trim($paymentMethod->name ?? ''));
        $normalizedSlug = strtolower(trim($paymentMethod->slug ?? ''));
        $codKeywords    = ['cash on delivery', 'cash-on-delivery', 'cash_on_delivery', 'cod'];

        $looksLikeCod = false;
        foreach ($codKeywords as $keyword) {
            if (str_contains($normalizedName, $keyword) || str_contains($normalizedSlug, $keyword)) {
                $looksLikeCod = true;
                break;
            }
        }

        if ($paymentType !== 'cod' && $looksLikeCod) {
            $paymentType = 'cod';
        }

        // ================================================================
        // 2. Dynamically build the validation rules based on the
        //    RESOLVED payment type (never trust the raw client tab name).
        //    - COD           -> screenshot NOT required
        //    - Mobile Wallet -> screenshot REQUIRED (Easypaisa / JazzCash)
        //    - Bank Transfer -> screenshot REQUIRED
        // ================================================================
        $rules = [
            'full_name'          => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'email'              => 'required|email|max:255',
            'city'               => 'required|string|max:100',
            'address'            => 'required|string',
            'payment_method_id'  => 'required|exists:payment_methods,id',
            'cart_data'          => 'required|json',
            'account_title'      => 'nullable|string|max:255',
            'account_number'     => 'nullable|string|max:255',
            'transaction_ref'    => 'nullable|string|max:255',
            'screenshot'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096', // Default: not required (COD)
        ];

        if ($paymentType === 'bank') {
            $rules['account_title']  = 'required|string|max:255';
            $rules['account_number'] = 'required|string|max:255';
            $rules['screenshot']     = 'required|image|mimes:jpg,jpeg,png,webp|max:4096';
        } elseif ($paymentType === 'mobile_wallet') {
            $rules['account_number'] = 'required|string|max:255'; // sender's wallet number
            $rules['screenshot']     = 'required|image|mimes:jpg,jpeg,png,webp|max:4096';
        }
        // 'cod' -> stays fully optional, no screenshot, no account fields required

        $customAttributes = [
            'account_title'  => 'your account / card holder name',
            'account_number' => 'your account number / IBAN / sender number',
            'screenshot'     => 'payment screenshot',
        ];

        // ================================================================
        // 3. RUN VALIDATION ONCE
        // ================================================================
        $validated = $request->validate($rules, [], $customAttributes);

        // ================================================================
        // 4. Process the order
        // ================================================================
        $cartItems = json_decode($validated['cart_data'], true);
        if (empty($cartItems)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * ($item['quantity'] ?? 1);
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $this->storeScreenshot($request->file('screenshot'));
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id'               => Auth::id(),
            'order_number'          => $orderNumber,
            'total_amount'          => $total,
            'payment_method_slug'   => $paymentMethod->slug,
            'payment_method_id'     => $paymentMethod->id,
            'payment_status'        => 'pending',
            'customer_name'         => $validated['full_name'],
            'customer_phone'        => $validated['phone'],
            'customer_email'        => $validated['email'],
            'city'                  => $validated['city'],
            'delivery_address'      => $validated['address'],
            'bank_name'             => $paymentType === 'bank' ? $paymentMethod->bank_name : null,
            'account_title'         => $validated['account_title'] ?? null,
            'account_number'        => $validated['account_number'] ?? null,
            'transaction_reference' => $validated['transaction_ref'] ?? null,
            'payment_screenshot'    => $screenshotPath,
        ]);

        foreach ($cartItems as $item) {
            $productId = $item['id'] ?? $item['product_id'] ?? $item['productId'] ?? $item['pid'] ?? $item['item_id'] ?? null;

            if (!$productId && isset($item['name'])) {
                $product = Product::where('name', $item['name'])->first();
                $productId = $product->id ?? null;
            }
            if (!$productId) {
                Log::warning('No product ID found for cart item, using dummy ID 1:', $item);
                $productId = 1;
            }

            $quantity = $item['quantity'] ?? $item['qty'] ?? 1;
            $price = $item['price'] ?? $item['unit_price'] ?? 0;

            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $productId,
                'quantity'    => $quantity,
                'unit_price'  => $price,
                'total_price' => $price * $quantity,
            ]);
        }

        session()->forget('cart');
        return redirect()->route('order.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function orderSuccess(Order $order)
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.payment_page', [
            'orderConfirmed' => true,
            'order'          => $order,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    private function storeScreenshot($file): string
    {
        $destination = public_path('payment-screenshots');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'payment-screenshots/' . $filename;
    }
}