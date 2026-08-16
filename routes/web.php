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
//------------------------------------------ UI Pages Routes start here -------------------------------------------------
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/menu/product/{id}', 'productDetail')->name('product.details');
    Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/services', 'services')->name('services');
    // Route::get('/payment', 'payment')->name('payment');
    Route::get('/cart', 'cart')->name('cart');
 Route::view('/view-orders', 'pages.view_orders')->name('view_orders');
   
});


Route::post('/contact-submit', [ContactController::class, 'store'])
    ->name('contacts.store');
Route::post('/contact-submit', [ContactController::class, 'store'])->name('contacts.store');


//-------------------------------------------------------------- auth routes --------------------------------
Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register/send-otp', [AuthController::class, 'registerOtp'])->name('register.otp');
Route::post('/register/verify', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    Route::get('/payment', [CheckoutController::class, 'index'])->name('payment');

    /* Admin Panel */
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', function () {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges to access this area.']);
            }
            return view('admin.dashboard');
        })->name('dashboard');


        // ⭐ ADDED — rider assignment route for the "Assign Rider" popup on
        // the Payments/Orders page. Produces route name: admin.orders.assign
        //-------------------------------------------------------------- order rider-assignment routes --------------------------------
        Route::prefix('orders')->name('orders.')->controller(OrderAssignmentController::class)->group(function () {
            Route::post('/{order}/assign', 'assign')->name('assign'); // admin.orders.assign
        });

        //-------------------------------------------------------------- rider management routes --------------------------------
        Route::prefix('riders')->name('riders.')->controller(RiderController::class)->group(function () {
            Route::get('/', 'index')->name('index');                          // admin.riders.index
            Route::get('/search', 'search')->name('search');                  // admin.riders.search
            Route::get('/create', 'create')->name('create');                  // admin.riders.create
            Route::post('/store', 'store')->name('store');                    // admin.riders.store
            Route::get('/edit/{id}', 'edit')->name('edit');                   // admin.riders.edit
            Route::put('/update/{id}', 'update')->name('update');             // admin.riders.update
            Route::delete('/delete/{id}', 'destroy')->name('destroy');        // admin.riders.destroy
            Route::patch('/toggle-status/{id}', 'toggleStatus')->name('toggle-status'); // admin.riders.toggle-status
        });

        //-------------------------------------------------------------- category management routes --------------------------------
        Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        //-------------------------------------------------------------- product management routes --------------------------------
        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('payment-methods')->name('payment-methods.')->controller(PaymentMethodController::class)->group(function () {
            Route::get('/', 'index')->name('index');                    // admin.payment-methods.index
            Route::get('/search', 'search')->name('search');            // admin.payment-methods.search
            Route::get('/create', 'create')->name('create');            // admin.payment-methods.create
            Route::post('/store', 'store')->name('store');              // admin.payment-methods.store
            Route::get('/edit/{id}', 'edit')->name('edit');             // admin.payment-methods.edit
            Route::put('/update/{id}', 'update')->name('update');       // admin.payment-methods.update
            Route::delete('/delete/{id}', 'destroy')->name('destroy');  // admin.payment-methods.destroy
        });

        //-------------------------------------------------------------- payment management routes (NEW) --------------------------------
        Route::prefix('payments')->name('payments.')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('index');                     // admin.payments.index
            Route::get('/search', 'search')->name('search');             // admin.payments.search
            Route::get('/{order}', 'show')->name('show');                // admin.payments.show
            Route::post('/{order}/approve', 'approve')->name('approve'); // admin.payments.approve
            Route::delete('/{order}', 'destroy')->name('destroy');       // admin.payments.destroy
        });

        // ✅ Reviews Management Routes
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/manage-gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::put('/manage-gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');


        Route::get('/about', [AboutController::class, 'index'])->name('about.index');
        Route::put('/about/{about}', [AboutController::class, 'update'])->name('about.update');
    });
});


// ============================================== PAYMENT & CHECKOUT ROUTES ==============================================
// ============================================== CHECKOUT ROUTES ==============================================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Generic order confirmation page (used for COD, mobile wallet and bank transfer orders)
Route::get('/order/confirmation/{order}', [CheckoutController::class, 'orderSuccess'])->name('order.success');

// // Safepay callbacks
// Route::get('/order/success/{order}', [CheckoutController::class, 'safepaySuccess'])->name('order.safepay.success');
// Route::get('/order/cancel/{order}', [CheckoutController::class, 'safepayCancel'])->name('order.safepay.cancel');