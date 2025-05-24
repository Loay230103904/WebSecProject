<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductsController;

Route::post('register', [UsersController::class, 'register']);
Route::post('login', [UsersController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('logout', [UsersController::class, 'logout']);
    Route::get('profile', [UsersController::class, 'profile']);

    Route::get('products', [ProductsController::class, 'list']);
    Route::post('products/{id}/purchase', [ProductsController::class, 'purchase']);
    Route::get('bought-products', [ProductsController::class, 'boughtProducts']);
});

