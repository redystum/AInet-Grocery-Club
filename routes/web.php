<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('login', [UserController::class, 'show_login'])->name('login');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::get('register', [UserController::class, 'show_register'])->name('register');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::get('logout', [UserController::class, 'logout'])->name('logout');
