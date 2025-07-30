<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerAuthController;


Route::get('test', fn() => response()->json(['success' => true]));

Route::post('register', [CustomerAuthController::class, 'register']);// register new customer
Route::post('login', [CustomerAuthController::class, 'login']);//login customer and get token

Route::prefix('customer')->group(function () {
    Route::middleware('auth:customer-api')->group(function () {
        //customer Api
        Route::get('show/{customer}', [CustomerController::class, 'show']); //View customer
        Route::get('all', [CustomerController::class, 'getAll']);//View all customers
        Route::put('update/{customer}', [CustomerController::class, 'update']);//Update customer
        Route::delete('delete/{customer}', [CustomerController::class, 'delete']);//Delete customer
        //Auth jwt with Customer model
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        Route::post('refresh', [CustomerAuthController::class, 'refresh']);
        //Service Api
        Route::get('customers/{id}/services', [ServiceController::class, 'customerServices']);
    });
});
