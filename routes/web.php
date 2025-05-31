<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\StockController;
use App\Http\Controllers\admin\SupplyController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*--------------------------------------------------------------------------
| Everyone routes
|---------------------------------------------------------------------------
| Routes that are accessible to everyone, guests and authenticated users.
|
*/
Route::get('/', function () {
    return view('pages.home');
})->name('home');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products?category={category}', [ProductController::class, 'index'])->name('products.category');


Route::name('product.')->prefix('product/{product}')->group(function () {
    Route::get('/', [ProductController::class, 'show'])->name('show');
    Route::get('add_to_cart', [ProductController::class, 'add_to_cart'])->name('add_to_cart');
});

/*--------------------------------------------------------------------------
| Guest routes
|---------------------------------------------------------------------------
| Routes that are accessible only to guests (not authenticated users).
|
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'show_login'])->name('login');
    Route::get('register', [AuthController::class, 'show_register'])->name('register');
    Route::middleware('throttle:6,1')->group(function () { // throttle max 6 requests per minute
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::get('/activate/{id}/{hash}', [AuthController::class, 'activate'])->name('activation');
    });
});

/*--------------------------------------------------------------------------
| Password reset routes
|---------------------------------------------------------------------------
| Routes for password reset functionality.
|
*/
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

/*--------------------------------------------------------------------------
| Authenticated routes
|---------------------------------------------------------------------------
| Routes that are accessible only to authenticated users.
|
*/
Route::middleware('auth')->group(function () {
    Route::get('profile', [UserController::class, 'show'])->name('profile');


    Route::name('orders')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('export', [OrderController::class, 'export'])->name('.export');

        Route::prefix('{order}')->group(function () {
            Route::get('receipt', [OrderController::class, 'receipt'])->name('.receipt');
            Route::get('cancel', [OrderController::class, 'cancel'])->name('.cancel');
            Route::post('cancel/confirm', [OrderController::class, 'cancelConfirm'])->name('.cancel.confirm');
        });
    });

    Route::middleware('notEmployee')->group(function () {
        Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
        Route::put('profile/update', [UserController::class, 'update'])->name('profile.update');
    });

    /*--------------------------------------------------------------------------
    | Admin routes
    |---------------------------------------------------------------------------
    | Routes that are accessible only to authenticated users with admin role.
    |
    */
    Route::middleware('board')->name('board.')->prefix('board/')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.dash');
        })->name('index');

        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::put('/stock/{product}/update', [StockController::class, 'update'])->name('stock.update');
        Route::name('restock.')->prefix('restock/')->group(function () {
            Route::get('auto', [StockController::class, 'restockAuto'])->name('auto');
            Route::post('store', [SupplyController::class, 'store'])->name('store');
            Route::get('{product}', [StockController::class, 'restock'])->name('product');
        });

        Route::name('supply.')->prefix('supply/')->group(function () {
            Route::get('/', [SupplyController::class, 'index'])->name('index');
            Route::put('{order}', [SupplyController::class, 'update'])->name('update');
            Route::get('{order}/cancel', [SupplyController::class, 'cancel'])->name('cancel');
            Route::get('{order}/complete', [SupplyController::class, 'complete'])->name('complete');
            Route::delete('destroy', [SupplyController::class, 'destroy'])->name('destroy');
        });
    });
});

/*!--------------------------------------------------------------------------
! DEVELOPMENT ONLY LOGIN ROUTE
!---------------------------------------------------------------------------
! This route is for development purposes only. It allows to log in as a
! user without credentials.
!
!*/
if (!app()->isProduction()) {
    Route::get('force_login/{user}', function (\App\Models\User $user) {
        auth()->loginUsingId($user->id, true);
        \App\Utils\ToastCreator::success('Logged in as ' . $user->name);
        return redirect()->back();
    })->name('force_login');

    // Error Pages
    Route::get('401', function () {
        abort(401);
    });
    Route::get('403', function () {
        abort(403);
    });
    Route::get('404', function () {
        abort(404);
    });
    Route::get('419', function () {
        abort(419);
    });
    Route::get('429', function () {
        abort(429);
    });
    Route::get('500', function () {
        abort(500);
    });
    Route::get('503', function () {
        abort(503);
    });
}
