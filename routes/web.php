<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*--------------------------------------------------------------------------
| Everyone routes
|---------------------------------------------------------------------------
| Routes that are accessible to everyone, guests and authenticated users.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products?category={category}', [ProductController::class, 'index'])->name('products.category');

Route::name('product.')->prefix('product/{product}')->group(function () {
    Route::get('/', [ProductController::class, 'show'])->name('show');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/update', [CartController::class, 'update']); // Para atualizar todas as quantidades
Route::put('/cart/checkout', [CartController::class, 'store'])->name('cart.store')->middleware('auth');
Route::put('/cart/{id}', [CartController::class, 'changeQuantity']); // AJAX update individual
Route::delete('/cart/{id}', [CartController::class, 'remove']); // AJAX remove
Route::get('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

Route::get('/after-purchase', function () {
    return view('pages/after-purchase');
})->name('after-purchase');

// Wishlist routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::get('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/check/{product}', [WishlistController::class, 'check'])->name('wishlist.check');
Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');


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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::name('orders')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('export', [OrderController::class, 'export'])->name('.export');

        Route::prefix('{order}')->group(function () {
            Route::get('receipt', [OrderController::class, 'receipt'])->name('.receipt');
            Route::get('cancel', [OrderController::class, 'cancel'])->name('.cancel');
            Route::post('cancel/confirm', [OrderController::class, 'cancelConfirm'])->name('.cancel.confirm');
        });
    });

    Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [UserController::class, 'update'])->name('profile.update')->middleware('notEmployee');
    Route::put('profile/update/employee', [UserController::class, 'updateEmployee'])->name('profile.update.employee');

    Route::name('card')->prefix('card/')->middleware('notEmployee')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('.index');
        Route::get('/create', [CardController::class, 'create'])->name('.create');
        Route::post('/create', [CardController::class, 'store'])->name('.store');
        Route::get('/charge', [CardController::class, 'charge'])->name('.charge');
        Route::put('/update', [CardController::class, 'update'])->name('.update');
    });

    /*--------------------------------------------------------------------------
    | Admin routes
    |---------------------------------------------------------------------------
    | Routes that are accessible only to authenticated users with admin role.
    |
    */
    Route::middleware('board')->name('board.')->prefix('board/')->group(function () {
        Route::get('/', function () {
            return redirect()->route('board.dashboard.index');
        })->name('index');

        Route::name('dashboard.')->prefix('dashboard/')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
            Route::name('export.')->prefix('export/')->group(function () {
                Route::get('orders', [AdminDashboardController::class, 'exportOrders'])->name('orders');
                Route::get('products', [AdminDashboardController::class, 'exportProducts'])->name('products');
                Route::get('users', [AdminDashboardController::class, 'exportMembers'])->name('users');
            });
        });

        Route::prefix('board/categories')->name('board.categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::put('/stock/{product}/update', [StockController::class, 'update'])->name('stock.update');
        Route::put('/stock/{product}/updateStock', [StockController::class, 'updateStock'])->name('stock.updateStock');
        Route::get('/stock/{product}/edit', [StockController::class, 'edit'])->name('stock.edit');
        Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
        Route::post('/stock/store', [StockController::class, 'store'])->name('stock.store');
        Route::post('/stock/{product}/delete', [StockController::class, 'delete'])->name('stock.delete');
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
            Route::get('{order}/receipt', [SupplyController::class, 'receipt'])->name('receipt');
        });

        Route::name('orders.')->prefix('orders/')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('{order}', [AdminOrderController::class, 'show'])->name('show');
            Route::put('{order}/confirm', [AdminOrderController::class, 'confirm'])->name('confirm');
            Route::get('{order}/cancel', [AdminOrderController::class, 'cancel'])->name('cancel.show');
            Route::post('{order}/cancel', [AdminOrderController::class, 'cancelByAdmin'])->name('cancel.store');
            Route::put('{order}/cancel/confirm', [AdminOrderController::class, 'cancelConfirm'])->name('cancel.confirm');
            Route::put('{order}/cancel/reject', [AdminOrderController::class, 'cancelReject'])->name('cancel.reject');
            Route::get('{order}/receipt', [AdminOrderController::class, 'receipt'])->name('receipt');
        });

        Route::resource('users', AdminUserController::class);
        Route::name('users.')->prefix('users/{user}/')->group(function () {
            Route::patch('unblock', [AdminUserController::class, 'unblock'])->name('unblock');
            Route::patch('block', [AdminUserController::class, 'block'])->name('block');
            Route::get('transactions', [AdminUserController::class, 'transactions'])->name('transactions');
            Route::get('resetPassword', [AdminUserController::class, 'resetPwd'])->name('resetPwd');
        });

        Route::get('/settings', function () {
            return view('pages.admin.settings');
        })->name('settings');
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
    Route::get('force_login/{user}', function (User $user) {
        auth()->logout();
        auth()->loginUsingId($user->id, true);
        \App\Utils\ToastCreator::success('Logged in as ' . $user->name);

        $user->notify(new \App\Notifications\NewLogin());

        return redirect()->back();
    })->name('force_login');

    Route::any('debug', function () {
        return view('debug');
    })->name('debug');

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
