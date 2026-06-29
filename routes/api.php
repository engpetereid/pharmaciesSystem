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
            Route::resource('drugs',      ApiDrugsController::class)->names('api.drugs');
            Route::resource('users',      ApiUsersController::class)->names('api.users');
            Route::resource('categories', ApiCategoriesController::class)->names('api.categories');
            Route::resource('pharmacies', ApiPharmaciesController::class)->names('api.pharmacies');
            Route::post('pharmacies/store-drugs', [ApiPharmaciesController::class, 'storeDrugs'])->name('api.pharmacies.store-drugs');
            Route::get('orders',                  [ApiOrdersController::class, 'index'])->name('api.orders.index');
            Route::patch('orders/{id}/accept',    [ApiOrdersController::class, 'acceptOrder'])->name('api.orders.accept');
            Route::resource('invoices', ApiInvoiceController::class)->names('api.invoices');
        });

        // Supervisor-only API routes
        Route::middleware('supervisor')->group(function () {
            Route::resource('supervisor-invoices', ApiSupervisorInvoiceController::class)->names('api.supervisor-invoices');
        });
    });
});
