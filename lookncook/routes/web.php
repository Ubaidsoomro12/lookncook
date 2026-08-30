<?php

use App\Http\Controllers\front\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CheckoutController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\backend\AboutController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\RiderController;
use App\Http\Controllers\Backend\OrderAssignmentController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\pos\TableController;
use App\Http\Controllers\Backend\StaffController; // <-- new
use App\Http\Controllers\Backend\BranchController;

//------------------------------------------ UI Pages Routes start here -------------------------------------------------
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/menu/product/{id}', 'productDetail')->name('product.details');
    Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/services', 'services')->name('services');
    Route::get('/cart', 'cart')->name('cart');
    Route::view('/view-orders', 'pages.view_orders')->name('view_orders');
});

Route::post('/contact-submit', [ContactController::class, 'store'])->name('contacts.store');

//-------------------------------------------------------------- auth routes --------------------------------
Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register/send-otp', [AuthController::class, 'registerOtp'])->name('register.otp');
Route::post('/register/verify', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');

Route::post('/forgot-password/send', [AuthController::class, 'sendResetOtp'])->name('password.forgot.send');
Route::post('/forgot-password/verify', [AuthController::class, 'updatePassword'])->name('password.forgot.submit');

// Review routes (frontend)
Route::post('/review/submit', [ReviewController::class, 'submit'])->name('review.submit');

//-------------------------------------------------------------- protected routes ---------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return '<div style="font-family:sans-serif; text-align:center; padding-top:10%;">
                    <h1 style="color:#ff2d7a;">Look n Cook Portal</h1>
                    <h2>Welcome, ' . e(auth()->user()->name) . '!</h2>
                    <form action="' . route('logout') . '" method="POST">' . csrf_field() . '<button type="submit">Logout</button></form>
                </div>';
    })->name('dashboard');

    // POS Dashboard for Manager
    Route::get('/pos/dashboard', function () {
        if (auth()->user()->role_id != 3) {
            return redirect('/')->withErrors(['email' => 'You do not have manager privileges.']);
        }
        return view('pos.pos_dashboard');
    })->name('pos.dashboard');

    Route::get('/payment', [CheckoutController::class, 'index'])->name('payment');

    /* Admin Panel */
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', function () {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges to access this area.']);
            }
            return view('admin.dashboard');
        })->name('dashboard');

        // ===== TABLES ROUTES =====
        Route::prefix('tables')->name('tables.')->controller(TableController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/search', 'search')->name('search');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/{id}/status', 'updateStatus')->name('status');
            Route::get('/{id}', 'show')->name('show');
        });

        // ===== STAFF ROUTES (replaces waiter) =====
        Route::prefix('staff')->name('staff.')->controller(StaffController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/search-users', 'searchUsers')->name('search-users'); // <-- new
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{id}', 'show')->name('show');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });


                // Branches Management Routes
Route::prefix('branches')->name('branches.')->controller(BranchController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/{id}/status', 'updateStatus')->name('status');
});

        // Banner Routes
        Route::resource('banners', BannerController::class);
        Route::get('banners/search', [BannerController::class, 'search'])->name('banners.search');

        // User Management Routes
        Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{user}', 'edit')->name('edit');
            Route::put('/update/{user}', 'update')->name('update');
            Route::delete('/delete/{user}', 'destroy')->name('destroy');
        });

        // Order rider-assignment routes
        Route::prefix('orders')->name('orders.')->controller(OrderAssignmentController::class)->group(function () {
            Route::post('/{order}/assign', 'assign')->name('assign');
        });

        // Rider management routes
        Route::prefix('riders')->name('riders.')->controller(RiderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::patch('/toggle-status/{id}', 'toggleStatus')->name('toggle-status');
        });

        // Category management routes
        Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        // Product management routes
        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        // Payment methods routes
        Route::prefix('payment-methods')->name('payment-methods.')->controller(PaymentMethodController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        // Payment management routes
        Route::prefix('payments')->name('payments.')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/{order}', 'show')->name('show');
            Route::post('/{order}/approve', 'approve')->name('approve');
            Route::delete('/{order}', 'destroy')->name('destroy');
        });

        // Reviews Management Routes
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Gallery Management
        Route::get('/manage-gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::put('/manage-gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');

        // About Management
        Route::get('/about', [AboutController::class, 'index'])->name('about.index');
        Route::put('/about/{about}', [AboutController::class, 'update'])->name('about.update');
    });
});

// ============================================== PAYMENT & CHECKOUT ROUTES ==============================================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/confirmation/{order}', [CheckoutController::class, 'orderSuccess'])->name('order.success');