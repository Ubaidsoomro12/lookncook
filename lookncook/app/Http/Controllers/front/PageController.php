<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\About;
use App\Models\Banner;

class PageController extends Controller
{
    public function home()
    {
        $banners = Banner::where('status', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();

        return view('index', compact('banners'));
    }

    public function menu()
    {
        $categories = \App\Models\Category::where('status', 'active')->orderBy('name')->get();
        $products = \App\Models\Product::where('status', 'active')->with('category')->latest()->get();
        return view('pages.menu', compact('categories', 'products'));
    }

    public function productDetail($id)
    {
        $product = Product::with('category')->where('status', 'active')->findOrFail($id);
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
        $gallery = Gallery::first();
        $banners = Banner::where('status', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();
        return view('pages.gallery', compact('gallery', 'banners'));
    }

    public function about()
    {
        $about = About::first();
        if (!$about) {
            $about = (object) [
                'title'          => 'Look N Cook Home Chef Catering Services',
                'subtitle'       => 'More Than Just Food',
                'subdescription' => 'Welcome to Look N Cook Home Chef. We bring delicious flavors, premium catering services, and unforgettable dining experiences.',
                'description'    => 'Our chefs prepare fresh meals with passion and creativity, making every event memorable.',
                'image1'         => 'about1.jpg',
                'image2'         => 'about2.jpg',
                'image3'         => 'about3.jpg'
            ];
        }
        $banners = Banner::where('status', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();
        return view('pages.about', compact('about', 'banners'));
    }

    public function contact()
    {
        $banners = Banner::where('status', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();
        return view('pages.contact', compact('banners'));
    }

    public function services()
    {
        $banners = Banner::where('status', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();
        return view('pages.services', compact('banners'));
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