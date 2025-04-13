<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('login', function () {return view('pages.login');})->name('login');
Route::get('register', function () {})->name('register');
