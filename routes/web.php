<?php

use App\Http\Controllers\Admin\AdminDashboard;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PharmaciesController;
use App\Http\Controllers\Admin\DrugsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\SupervisorDashboard;
use App\Http\Controllers\Supervisor\SupervisorInvoiceController;
use App\Http\Controllers\Supervisor\WarehouseController; // Fix C-2: was referencing undefined WarehouseController (uppercase W) while only importing lowercase warehouseController — fatal error on Linux
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return match (true) {
        Auth::user()->role === 'admin'      => redirect()->route('admin.dashboard'),
        Auth::user()->role === 'supervisor' => redirect()->route('supervisor.dashboard'),
        default                             => abort(403, 'Unauthorized role.'),
    };
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    Route::resource('drugs', DrugsController::class)->names('admin.drugs');
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class)->names('admin.users');
    Route::resource('categories', CategoriesController::class)->names('admin.categories');
    Route::resource('pharmacies', PharmaciesController::class)->names('admin.pharmacies');

    Route::get('add/{id}', [PharmaciesController::class, 'add'])->name('admin.pharmacies.add');
    Route::post('store', [PharmaciesController::class, 'storeDrugs'])->name('admin.pharmacies.storeDrugs');

    Route::get('orders', [OrdersController::class, 'index'])->name('admin.orders');

    Route::patch('orders/{id}/accept', [OrdersController::class, 'acceptOrder'])
        ->name('admin.orders.accept');

    Route::resource('invoices', InvoiceController::class)->names('admin.invoices');
    Route::get('notifications', [NotificationsController::class, 'index'])->name('admin.notifications');
});


/*
|--------------------------------------------------------------------------
| Supervisor routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'supervisor'])->group(function () {

    Route::get('/supervisor/dashboard', [SupervisorDashboard::class, 'index'])
        ->name('supervisor.dashboard');

    Route::resource('invoices', SupervisorInvoiceController::class)
        ->names('supervisor.invoices');

    Route::get('warehouses', [WarehouseController::class, 'index'])
        ->name('supervisor.warehouses');

    Route::get('minimum/{id}', [WarehouseController::class, 'show'])
        ->name('supervisor.show.minimum');

    Route::post('minimum', [WarehouseController::class, 'minimum'])
        ->name('supervisor.minimum');

    Route::get('require', [WarehouseController::class, 'require'])
        ->name('supervisor.require');

    Route::post('makeOrder', [WarehouseController::class, 'makeOrder'])
        ->name('supervisor.makeOrder');

    Route::delete('deleteOrder/{id}', [WarehouseController::class, 'deleteOrder'])
        ->name('supervisor.deleteOrder');
});


/*
|--------------------------------------------------------------------------
| Profile routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
