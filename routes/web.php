<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});



use App\Http\Controllers\Web\ProductsController;

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
Route::post('/products/{id}/purchase', [ProductsController::class, 'purchase'])->name('products_purchase');
Route::get('/bought-products', [ProductsController::class, 'boughtProducts'])->name('brought_products');



use App\Http\Controllers\Web\UsersController;

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



Route::get('bought_products', [ProductsController::class, 'boughtProducts'])->name('bought_products');
Route::post('bought-products/{id}/delivered', [ProductsController::class, 'markDelivered'])->name('product_delivered');
Route::post('bought-products/{id}/refused', [ProductsController::class, 'markRefused'])->name('product_refused');



Route::get('/stock-operations', [ProductsController::class, 'stockOperations'])->name('stock_operations');
Route::post('/products/{id}/increase-stock', [ProductsController::class, 'increaseStock'])->name('increase_stock');
