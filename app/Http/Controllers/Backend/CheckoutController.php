<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use Safepay\SafepayClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('pages.payment_page', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'city'            => 'required|string|max:100',
            'address'         => 'required|string',
            'payment_method'  => 'required|in:cod,safepay',
            'cart_data'       => 'required|json',
            'bank_name'       => 'nullable|string|max:255',
            'account_title'   => 'nullable|string|max:255',
            'account_number'  => 'nullable|string|max:255',
            'transaction_ref' => 'nullable|string|max:255',
        ]);

        Log::info('RAW CART_DATA: ' . $validated['cart_data']);

        $cartItems = json_decode($validated['cart_data'], true);
        if (empty($cartItems)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        Log::info('DECODED CART ITEMS:', $cartItems);

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * ($item['quantity'] ?? 1);
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id'               => Auth::id(),
            'order_number'          => $orderNumber,
            'total_amount'          => $total,
            'payment_method_slug'   => $validated['payment_method'],
            'payment_status'        => 'pending',
            'customer_name'         => $validated['full_name'],
            'customer_phone'        => $validated['phone'],
            'customer_email'        => $validated['email'],
            'city'                  => $validated['city'],
            'delivery_address'      => $validated['address'],
            'bank_name'             => $validated['bank_name'] ?? null,
            'account_title'         => $validated['account_title'] ?? null,
            'account_number'        => $validated['account_number'] ?? null,
            'transaction_reference' => $validated['transaction_ref'] ?? null,
        ]);

        $savedCount = 0;
        foreach ($cartItems as $item) {
            $productId = $item['id'] ??
                        $item['product_id'] ??
                        $item['productId'] ??
                        $item['pid'] ??
                        $item['item_id'] ??
                        null;

            if (!$productId && isset($item['name'])) {
                $product = Product::where('name', $item['name'])->first();
                if ($product) {
                    $productId = $product->id;
                    Log::info("Found product by name: {$item['name']} -> ID {$productId}");
                }
            }

            if (!$productId) {
                Log::warning('No product ID found for item, using dummy ID 1:', $item);
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
            $savedCount++;
        }

        Log::info("Order #{$order->id} saved with {$savedCount} items.");

        if ($validated['payment_method'] === 'safepay') {
            return $this->createSafepaySession($order);
        }

        session()->forget('cart');
        return redirect()->route('order.success', $order->id)
                         ->with('success', 'Order placed successfully!');
    }

    private function createSafepaySession(Order $order)
    {
        $apiBase = config('services.safepay.env') === 'sandbox'
            ? 'https://sandbox.api.getsafepay.com'
            : 'https://api.getsafepay.com';

        $safepay = new SafepayClient([
            'api_key'  => config('services.safepay.secret_key'),
            'api_base' => $apiBase,
        ]);

        // Step 1: Create the payment session
        try {
            $session = $safepay->order->setup([
                'merchant_api_key' => config('services.safepay.public_key'),
                'intent'           => 'CYBERSOURCE',
                'mode'             => 'payment',
                'entry_mode'       => 'raw',
                'currency'         => 'PKR',
                'amount'           => (int) round($order->total_amount * 100), // amount in paisas
                'metadata'         => [
                    'order_id' => (string) $order->id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Safepay payment session creation failed: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Unable to start payment. Please try again.']);
        }

        Log::info('Safepay session response: ' . json_encode($session));

        $tracker = $session->tracker->token ?? null;

        if (!$tracker || !is_string($tracker)) {
            Log::error('Safepay session created but no valid tracker token found: ' . json_encode($session));
            return back()->withErrors(['payment' => 'Payment setup failed. Please try again.']);
        }

        // Step 2: Create a short-lived authentication token
        try {
            $authResponse = $safepay->passport->create();
        } catch (\Exception $e) {
            Log::error('Safepay authentication token creation failed: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Unable to start payment. Please try again.']);
        }

        Log::info('Safepay auth token response: ' . json_encode($authResponse));

        $tbt = $authResponse->token ?? null;

        if (!$tbt || !is_string($tbt)) {
            Log::error('Safepay auth response had no valid tbt token: ' . json_encode($authResponse));
            return back()->withErrors(['payment' => 'Payment setup failed. Please try again.']);
        }

        $order->stripe_payment_intent_id = $tracker;
        $order->save();

        // Step 3: Generate the Checkout URL
        try {
            $checkoutURL = \Safepay\Checkout::constructURL([
                'environment'  => config('services.safepay.env'),
                'tracker'      => $tracker,
                'tbt'          => $tbt,
                'source'       => 'hosted',
                'redirect_url' => route('order.safepay.success', $order->id),
                'cancel_url'   => route('order.safepay.cancel', $order->id),
            ]);
        } catch (\UnexpectedValueException $e) {
            Log::error('Safepay checkout URL construction failed: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Payment setup failed. Please try again.']);
        }

        return redirect($checkoutURL);
    }

    public function safepaySuccess(Request $request, Order $order)
    {
        // NOTE: for real security, verify payment status via a Safepay webhook
        // rather than trusting this redirect alone (a user could hit this URL manually).
        $order->payment_status = 'paid';
        $order->save();

        session()->forget('cart');
        return $this->orderSuccess($order);
    }

    public function safepayCancel(Order $order)
    {
        return redirect()->route('checkout.index')
                         ->with('error', 'Payment was cancelled. You can try again.');
    }

    public function orderSuccess(Order $order)
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('pages.payment_page', [
            'orderConfirmed' => true,
            'order'          => $order,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}