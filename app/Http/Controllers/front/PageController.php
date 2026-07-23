<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function home()
    {
        return view('index');
    }

    public function menu()
    {
        return view('pages.menu');
    }

    public function gallery()
    {
        return view('pages.gallery');
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