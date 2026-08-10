<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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

    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.pages.products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        $products = Product::with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhereHas('category', function ($cat) use ($q) {
                          $cat->where('name', 'like', "%{$q}%");
                      });
            })
            ->latest()
            ->get();

        return response()->json([
            'products' => $products->map(function ($p) {
                return [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'description' => $p->description,
                    'price'       => $p->price,
                    'sale_price'  => $p->sale_price,
                    'weight'      => $p->weight,
                    'variants'    => is_array($p->variation) ? $p->variation : null,
                    'category'    => $p->category->name ?? '—',
                    'status'      => $p->status,
                    'image'       => $p->image ? asset($p->image) : null,
                    'edit_url'    => route('admin.products.edit', $p->id),
                    'delete_url'  => route('admin.products.destroy', $p->id),
                ];
            }),
        ]);
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.pages.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'          => 'required|exists:categories,id',
            'name'                 => 'required|string|min:2|max:255',
            'description'          => 'required|string|min:10|max:2000',
            'price'                => 'required|numeric|min:0',
            'sale_price'           => 'required|numeric|min:0',
            'weight'               => 'required|string|max:255',
            'variants'             => 'nullable|array',
            'variants.*.weight'    => 'required_with:variants|string|max:100',
            'variants.*.old_price' => 'nullable|numeric|min:0',
            'variants.*.price'     => 'required_with:variants|numeric|min:0',
            'image'                => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'               => 'required|in:active,inactive',
        ], [
            'category_id.required'      => 'Please select a category.',
            'category_id.exists'        => 'Selected category is invalid.',
            'name.required'             => 'Product name is required.',
            'description.required'      => 'Description is required.',
            'description.min'           => 'Description must be at least 10 characters.',
            'price.required'            => 'Old price is required.',
            'price.numeric'             => 'Old price must be a valid number.',
            'sale_price.required'       => 'Sale price is required.',
            'sale_price.numeric'        => 'Sale price must be a valid number.',
            'weight.required'           => 'Weight / portion is required.',
            'variants.*.weight.required_with' => 'Each variant needs a weight/size.',
            'variants.*.price.required_with'  => 'Each variant needs a price.',
            'image.required'            => 'Product image is required.',
            'image.mimes'               => 'Image must be jpg, jpeg, png, or webp.',
            'image.max'                 => 'Image size must not exceed 4MB.',
            'status.required'           => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $imagePath = $this->storeImage($request->file('image'));

        $product = new Product();
        $product->category_id = $request->input('category_id');
        $product->name        = trim((string) $request->input('name'));
        $product->description = trim((string) $request->input('description'));
        $product->price       = $request->input('price');
        $product->sale_price  = $request->input('sale_price');
        $product->weight      = trim((string) $request->input('weight'));
        $product->variation   = $this->normalizeVariants($request->input('variants'));
        $product->image       = $imagePath;
        $product->status      = $request->input('status');
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.pages.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id'          => 'required|exists:categories,id',
            'name'                 => 'required|string|min:2|max:255',
            'description'          => 'required|string|min:10|max:2000',
            'price'                => 'required|numeric|min:0',
            'sale_price'           => 'required|numeric|min:0',
            'weight'               => 'required|string|max:255',
            'variants'             => 'nullable|array',
            'variants.*.weight'    => 'required_with:variants|string|max:100',
            'variants.*.old_price' => 'nullable|numeric|min:0',
            'variants.*.price'     => 'required_with:variants|numeric|min:0',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'               => 'required|in:active,inactive',
        ], [
            'category_id.required'      => 'Please select a category.',
            'category_id.exists'        => 'Selected category is invalid.',
            'name.required'             => 'Product name is required.',
            'description.required'      => 'Description is required.',
            'description.min'           => 'Description must be at least 10 characters.',
            'price.required'            => 'Old price is required.',
            'price.numeric'             => 'Old price must be a valid number.',
            'sale_price.required'       => 'Sale price is required.',
            'sale_price.numeric'        => 'Sale price must be a valid number.',
            'weight.required'           => 'Weight / portion is required.',
            'variants.*.weight.required_with' => 'Each variant needs a weight/size.',
            'variants.*.price.required_with'  => 'Each variant needs a price.',
            'image.mimes'                => 'Image must be jpg, jpeg, png, or webp.',
            'image.max'                  => 'Image size must not exceed 4MB.',
            'status.required'            => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $product->image = $this->storeImage($request->file('image'));
        }

        $product->category_id = $request->input('category_id');
        $product->name        = trim((string) $request->input('name'));
        $product->description = trim((string) $request->input('description'));
        $product->price       = $request->input('price');
        $product->sale_price  = $request->input('sale_price');
        $product->weight      = trim((string) $request->input('weight'));
        $product->variation   = $this->normalizeVariants($request->input('variants'));
        $product->status      = $request->input('status');
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->deleteImage($product->image);
        $product->delete();

        return response()->json(['success' => true]);
    }

    private function normalizeVariants($variants)
    {
        if (!is_array($variants) || empty($variants)) {
            return null;
        }

        $clean = collect($variants)
            ->filter(function ($v) {
                return !empty($v['weight']) && isset($v['price']) && $v['price'] !== '';
            })
            ->map(function ($v) {
                return [
                    'weight'    => trim((string) $v['weight']),
                    'old_price' => isset($v['old_price']) && $v['old_price'] !== ''
                        ? (float) $v['old_price']
                        : null,
                    'price'     => (float) $v['price'],
                ];
            })
            ->values()
            ->all();

        return count($clean) > 0 ? $clean : null;
    }

    private function storeImage($file)
    {
        $destination = public_path('products');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'products/' . $filename;
    }

    private function deleteImage($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}