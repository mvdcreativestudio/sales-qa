<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;

use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\OmnichannelController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ClientController;

Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return view('content.dashboard.dashboard-mvd');
    })->name('dashboard');
    Route::get('/clients/datatable', [ClientController::class, 'datatable'])->name('clients.datatable');

    Route::resource('raw-materials', RawMaterialController::class);
});

// Clients
Route::resource('clients', ClientController::class);


// Omnicanalidad
Route::get('omnichannel', [OmnichannelController::class, 'index'])->name('omnichannel');

// E-Commerce
Route::get('shop', [EcommerceController::class, 'index'])->name('shop');
Route::get('store', [EcommerceController::class, 'store'])->name('store');
Route::get('checkout', [EcommerceController::class, 'checkout'])->name('checkout');

// E-Commerce - Backoffice
Route::get('/ecommerce/orders', [EcommerceController::class, 'orders'])->name('orders');
Route::get('/ecommerce/products', [EcommerceController::class, 'products'])->name('products');
Route::get('/ecommerce/marketing', [EcommerceController::class, 'marketing'])->name('marketing');
Route::get('/ecommerce/settings', [EcommerceController::class, 'settings'])->name('settings');

// CRM
Route::get('crm', [CrmController::class, 'index'])->name('crm');

// Contabilidad
Route::get('receipts', [AccountingController::class, 'receipts'])->name('receipts');
Route::get('entries', [AccountingController::class, 'entries'])->name('entries');
Route::get('entrie', [AccountingController::class, 'entrie'])->name('entrie');

    // Invoices
    Route::resource('invoices', InvoiceController::class);

