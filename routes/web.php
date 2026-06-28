<?php

use App\Http\Controllers\Admin\AreaController as AdminAreaController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Knet\HandleController as KnetHandleController;
use App\Http\Controllers\Knet\ResponseController as KnetResponseController;
use App\Http\Controllers\PaymentResponseController;
use Asciisd\Knet\Http\Middleware\VerifyKnetResponseSignature;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/orders', [HomeController::class, 'store'])->name('orders.store');
Route::get('/governorates/{governorate}/areas', [HomeController::class, 'areas'])->name('governorates.areas');

Route::post('/payment/knet-response', KnetResponseController::class)
    ->middleware(VerifyKnetResponseSignature::class)
    ->name('knet.app.response');

Route::match(['get', 'post'], '/knet/handle', KnetHandleController::class)->name('knet.handle.fallback');

Route::get('/payment-result', [PaymentResponseController::class, 'show'])->name('payment.result');
Route::get('/payment-error', [PaymentResponseController::class, 'errorPage'])->name('payment.error');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('governorates', GovernorateController::class)->except(['show']);
        Route::resource('governorates.areas', AdminAreaController::class)->except(['show']);

        Route::resource('schools', SchoolController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::get('payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
        Route::put('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
        Route::post('orders/bulk-status', [AdminOrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
        Route::post('orders/bulk-print', [AdminOrderController::class, 'bulkPrint'])->name('orders.bulk-print');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
    });
});
