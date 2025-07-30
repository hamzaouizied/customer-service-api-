<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerAuthController;

Route::post('register', [CustomerAuthController::class, 'register']);// register new customer
Route::post('login', [CustomerAuthController::class, 'login']);//login customer and get token

Route::middleware('auth:customer-api')->group(function () {
    Route::prefix('customer')->group(function () {
        //customer Api
        Route::get('show/{customer}', [CustomerController::class, 'show']); //View customer
        Route::get('all', [CustomerController::class, 'getAll']);//View all customers
        Route::patch('update/{customer}', [CustomerController::class, 'update']);//Update customer
        Route::delete('delete/{customer}', [CustomerController::class, 'delete']);//Delete customer
        //Auth jwt with Customer model
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        Route::post('refresh', [CustomerAuthController::class, 'refresh']);
    });
    Route::prefix('service')->group(function () {
        //Service Api
        Route::post('store', [ServiceController::class, 'store']); //create a service for customer
        Route::get('show/{service}', [ServiceController::class, 'show']); //View Service
        Route::get('customer/{customer}', [ServiceController::class, 'customerServices']);// view service of a customer
        Route::patch('update/{service}', [ServiceController::class, 'update']);//Update service
        Route::delete('delete/{service}', [ServiceController::class, 'delete']);//Delete service
    });
});

