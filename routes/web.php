<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('do_logout');

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Protected routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Products routes
    Route::get('/products', [ProductsController::class, 'list'])->name('products_list');
    Route::get('/products/purchased', [ProductsController::class, 'purchasedProducts'])->name('products_purchased');
    Route::post('/products/{product}/buy', [ProductsController::class, 'buy'])->name('products_buy');
    Route::get('/products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
    Route::post('/products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
    Route::delete('/products/{product}', [ProductsController::class, 'delete'])->name('products_delete');
    Route::post('/products/{product}/review', [ProductsController::class, 'review'])->name('products_review');
});