<?php

use App\Http\Controllers\Api\ApiCategoriesController;
use App\Http\Controllers\Api\ApiDrugsController;
use App\Http\Controllers\Api\ApiInvoiceController;
use App\Http\Controllers\Api\ApiOrdersController;
use App\Http\Controllers\Api\ApiPharmaciesController;
use App\Http\Controllers\Api\ApiSupervisorInvoiceController;
use App\Http\Controllers\Api\ApiUsersController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /*
     * New Feature: rate-limit authentication endpoints to 10 attempts per
     * minute to mitigate brute-force credential attacks.
     */
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/register', [RegisterController::class, 'register']);
        Route::post('/login',    [RegisterController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {

        /*
         * New Feature: logout endpoint — revokes the current Sanctum token.
         */
        Route::post('/logout', function (Request $request) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        });

        // Admin-only API routes
        Route::middleware('admin')->group(function () {
            Route::resource('drugs',      ApiDrugsController::class);
            Route::resource('users',      ApiUsersController::class);
            Route::resource('categories', ApiCategoriesController::class);
            Route::resource('pharmacies', ApiPharmaciesController::class);
            Route::post('pharmacies/store-drugs', [ApiPharmaciesController::class, 'storeDrugs']);
            Route::get('orders',                  [ApiOrdersController::class, 'index']);
            Route::patch('orders/{id}/accept',    [ApiOrdersController::class, 'acceptOrder']);
            Route::resource('invoices', ApiInvoiceController::class);
        });

        // Supervisor-only API routes
        Route::middleware('supervisor')->group(function () {
            Route::resource('supervisor-invoices', ApiSupervisorInvoiceController::class);
        });
    });
});
