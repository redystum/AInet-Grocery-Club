<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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


/*--------------------------------------------------------------------------
| Guest routes
|---------------------------------------------------------------------------
| Routes that are accessible only to guests (not authenticated users).
|
*/
Route::get('login', [AuthController::class, 'show_login'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'show_register'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register');

/*--------------------------------------------------------------------------
| Authenticated routes
|---------------------------------------------------------------------------
| Routes that are accessible only to authenticated users.
|
*/
Route::middleware('auth')->group(function () {
    Route::get('profile', [UserController::class, 'show'])->name('profile');

    Route::middleware('notEmployee')->group(function () {
        Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
        Route::put('profile/update', [UserController::class, 'update'])->name('profile.update');
    });
});

/*--------------------------------------------------------------------------
| Admin routes
|---------------------------------------------------------------------------
| Routes that are accessible only to authenticated users with admin role.
|
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');



/*!--------------------------------------------------------------------------
! DEVELOPMENT ONLY LOGIN ROUTE
!---------------------------------------------------------------------------
! This route is for development purposes only. It allows to log in as a
! user without credentials.
!
!*/
if (!app()->isProduction()) {
    Route::get('force_login/{user}', function ($user) {
        auth()->loginUsingId($user);
        return redirect()->back();
    })->name('force_login');
}
