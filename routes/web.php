<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\SocialController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});




Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
Route::post('/products/{id}/purchase', [ProductsController::class, 'purchase'])->name('products_purchase');
Route::get('/bought-products', [ProductsController::class, 'boughtProducts'])->name('brought_products');




Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');


Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users', [UsersController::class, 'index'])->name('users_index');
Route::get('/users/reset/{user}', [UsersController::class, 'reset'])->name('users_reset');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('bought_products', [ProductsController::class, 'boughtProducts'])->name('bought_products');
Route::post('bought-products/{id}/delivered', [ProductsController::class, 'markDelivered'])->name('product_delivered');
Route::post('bought-products/{id}/refused', [ProductsController::class, 'markRefused'])->name('product_refused');



Route::get('/stock-operations', [ProductsController::class, 'stockOperations'])->name('stock_operations');
Route::post('/products/{id}/increase-stock', [ProductsController::class, 'increaseStock'])->name('increase_stock');


Route::get('verify', [UsersController::class, 'verify'])->name('verify');

//password reset

Route::get('/forgot-password', [UsersController::class, 'forgotPasswordForm'])->name('forgot_password');
Route::post('/forgot-password', [UsersController::class, 'sendTemporaryPassword'])->name('forgot_password.submit');


Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback/{provider}', [SocialController::class, 'callback'])->name('auth.callback');

