<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PaymentMethodController extends Controller
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

    public function index()
    {
        $methods = PaymentMethod::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('admin.pages.payment-methods.index', compact('methods'));
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        $methods = PaymentMethod::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('bank_name', 'like', "%{$q}%")
                    ->orWhere('account_title', 'like', "%{$q}%")
                    ->orWhere('account_number', 'like', "%{$q}%");
            })
            ->orderBy('sort_order')
            ->get()
            ->map(function ($m) {
                return [
                    'id'             => $m->id,
                    'name'           => $m->name,
                    'type'           => $m->type,
                    'display_icon'   => $m->display_icon,
                    'icon'           => $m->icon,
                    'logo_url'       => $m->logo ? asset($m->logo) : null,
                    'bank_name'      => $m->bank_name,
                    'account_title'  => $m->account_title,
                    'account_number' => $m->account_number,
                    'iban'           => $m->iban,
                    'deep_link'      => $m->deep_link,
                    'is_active'      => $m->is_active,
                    'sort_order'     => $m->sort_order,
                    'edit_url'       => route('admin.payment-methods.edit', $m->id),
                    'delete_url'     => route('admin.payment-methods.destroy', $m->id),
                ];
            });

        return response()->json(['methods' => $methods]);
    }

    public function create()
    {
        return view('admin.pages.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:cod,mobile_wallet,bank',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'account_title'  => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban'           => 'nullable|string|max:255',
            'deep_link'      => 'nullable|url|max:255',
        ]);

        if ($validated['type'] === 'bank') {
            $validated['bank_name'] = $validated['name'];
        } else {
            $validated['bank_name'] = null;
        }

        // Handle logo upload (stored separately, never mass-assigned raw)
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeLogo($request->file('logo'));
        } else {
            unset($validated['logo']);
        }

        $validated['slug']        = Str::slug($validated['name']);
        $validated['icon']        = 'fa-credit-card';
        $validated['description'] = null;
        $validated['sort_order']  = PaymentMethod::max('sort_order') + 1;
        $validated['is_active']   = $request->has('is_active') ? 1 : 0;

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.pages.payment-methods.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:cod,mobile_wallet,bank',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'account_title'  => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban'           => 'nullable|string|max:255',
            'deep_link'      => 'nullable|url|max:255',
        ]);

        if ($validated['type'] === 'bank') {
            $validated['bank_name'] = $validated['name'];
        } else {
            $validated['bank_name'] = null;
        }

        if ($validated['name'] !== $method->name) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = $method->slug;
        }

        // Handle logo: replace, remove, or keep as-is
        if ($request->hasFile('logo')) {
            $this->deleteLogoFile($method->logo);
            $validated['logo'] = $this->storeLogo($request->file('logo'));
        } elseif ($request->boolean('remove_logo')) {
            $this->deleteLogoFile($method->logo);
            $validated['logo'] = null;
        } else {
            $validated['logo'] = $method->logo;
        }

        $validated['icon']        = $validated['icon'] ?? $method->icon ?? 'fa-credit-card';
        $validated['description'] = $validated['description'] ?? $method->description ?? null;
        $validated['sort_order']  = $validated['sort_order'] ?? $method->sort_order;
        $validated['is_active']   = $request->has('is_active') ? 1 : 0;

        $method->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        if ($method->orders()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This payment method has existing orders and cannot be deleted. Deactivate it instead.',
            ], 422);
        }

        $this->deleteLogoFile($method->logo);
        $method->delete();
        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->is_active = !$method->is_active;
        $method->save();

        return response()->json([
            'success' => true,
            'is_active' => $method->is_active,
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:payment_methods,id',
        ]);

        foreach ($request->orders as $index => $id) {
            PaymentMethod::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Store an uploaded logo file in public/payment-method-logos
     * and return its relative path for the "logo" column.
     */
    private function storeLogo($file): string
    {
        $destination = public_path('payment-method-logos');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'payment-method-logos/' . $filename;
    }

    /**
     * Delete a previously stored logo file from disk, if it exists.
     */
    private function deleteLogoFile(?string $logoPath): void
    {
        if ($logoPath) {
            $fullPath = public_path($logoPath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}