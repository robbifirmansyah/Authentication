<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SendEmailController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::controller(LoginRegisterController::class)->group(function() {
 Route::get('/register', 'register')->name('register');
 Route::post('/store', 'store')->name('store');
 Route::get('/login', 'login')->name('login');
 Route::post('/authenticate', 'authenticate')->name('authenticate');
 Route::get('/dashboard', 'dashboard')->name('dashboard');
 Route::post('/logout', 'logout')->name('logout');
});

Route::get('restricted', function () {
    return redirect() ->route('dashboard')->withSuccess('Anda berusia lebih dari 18 tahun!');
})->middleware('checkage');

Route::get('/admin', function () {
    return view('auth.dashboard')->with('message', 'Anda adalah admin');
})->middleware(['auth', 'admin']);

Route::resource('/users', UserController::class);

Route::resource('/gallery', GalleryController::class);

Route::get('/send-email', [SendEmailController::class, 'index'])->name('kirim-email');

Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');