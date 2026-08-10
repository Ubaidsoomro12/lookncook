<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Gallery;

class PageController extends Controller
{
    public function home()
    {
        return view('index');
    }

    public function menu()
    {
        // Using the absolute fully qualified class name guarantees Laravel finds the right path
        $categories = \App\Models\Category::where('status', 'active')->orderBy('name')->get();

        $products = \App\Models\Product::where('status', 'active')->with('category')->latest()->get();

        return view('pages.menu', compact('categories', 'products'));
    }
    public function productDetail($id)
    {
        // Fetch the active product or fail with a 404 error
        $product = Product::with('category')->where('status', 'active')->findOrFail($id);

        // Fetch similar items from the same category, excluding current product
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        return view('pages.product_detail', compact('product', 'similarProducts'));
    }

 public function gallery()
{
    $gallery = Gallery::first(); // Get the first (and only) gallery record
    return view('pages.gallery', compact('gallery'));
}
    public function about()
    {
        return view('pages.about');
    }
    public function contact()
    {
        return view('pages.contact');
    }
    public function services()
    {
        return view('pages.services');
    }
    public function cart()
    {
        return view('pages.cart_page');
    }
    public function payment()
    {
        return view('pages.payment_page');
    }

}