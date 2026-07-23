<?php

use App\Http\Controllers\front\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\PageController;
use App\Http\Controllers\AuthController;


//------------------------------------------ UI Pages Routes start here -------------------------------------------------
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/services', 'services')->name('services');
    Route::get('/payment', 'payment')->name('payment');
    Route::get('/cart', 'cart')->name('cart');
});


Route::post('/contact-submit', [ContactController::class, 'store'])
    ->name('contacts.store');

//-------------------------------------------------------------- auth routes --------------------------------
Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register/send-otp', [AuthController::class, 'registerOtp'])->name('register.otp');
Route::post('/register/verify', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/forgot-password/send', [AuthController::class, 'sendResetOtp'])->name('password.forgot.send');
Route::post('/forgot-password/verify', [AuthController::class, 'updatePassword'])->name('password.forgot.submit');

//-------------------------------------------------------------- protected routes ---------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return '<div style="font-family:sans-serif; text-align:center; padding-top:10%;">
                    <h1 style="color:#ff2d7a;">Look n Cook Portal</h1>
                    <h2>Welcome, ' . e(auth()->user()->name) . '!</h2>
                    <form action="' . route('logout') . '" method="POST">' . csrf_field() . '<button type="submit">Logout</button></form>
                </div>';
    })->name('dashboard');

    Route::get('/payment', function () {
        return view('pages.payment_page');
    })->name('payment');

    /* Admin Panel */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges to access this area.']);
            }
            // FIXED EXACT PATH
            return view('admin.dashboard');
        })->name('dashboard');
    });
}); 