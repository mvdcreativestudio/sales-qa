<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;

use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\OmnichannelController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\StoreController;

Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return view('content.dashboard.dashboard-mvd');
    })->name('dashboard');

    // Tiendas / Franquicias
    Route::resource('stores', StoreController::class);
    Route::get('stores/{store}/manage-users', [StoreController::class, 'manageUsers'])->name('stores.manageUsers');
    Route::post('stores/{store}/associate-user', [StoreController::class, 'associateUser'])->name('stores.associateUser');
    Route::post('stores/{store}/disassociate-user', [StoreController::class, 'disassociateUser'])->name('stores.disassociateUser');

    Route::resource('raw-materials', RawMaterialController::class);
});

// Omnicanalidad
Route::get('omnichannel', [OmnichannelController::class, 'index'])->name('omnichannel');

// E-Commerce
Route::get('shop', [EcommerceController::class, 'index'])->name('shop');
Route::get('store', [EcommerceController::class, 'store'])->name('store');
Route::get('checkout', [EcommerceController::class, 'checkout'])->name('checkout');

// CRM
Route::get('crm', [CrmController::class, 'index'])->name('crm');

// Contabilidad
Route::get('accounting', [AccountingController::class, 'index'])->name('accounting');
Route::get('receipts', [AccountingController::class, 'receipts'])->name('receipts');
Route::get('entries', [AccountingController::class, 'entries'])->name('entries');
Route::get('entrie', [AccountingController::class, 'entrie'])->name('entrie');
