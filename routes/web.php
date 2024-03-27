<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;

use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\OmnichannelController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierOrderController;

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

    // Roles
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/manage-users', [RoleController::class, 'manageUsers'])->name('roles.manageUsers');
    Route::post('roles/{role}/associate-user', [RoleController::class, 'associateUser'])->name('roles.associateUser');
    Route::post('roles/{role}/disassociate-user', [RoleController::class, 'disassociateUser'])->name('roles.disassociateUser');
    Route::get('roles/{role}/manage-permissions', [RoleController::class, 'managePermissions'])->name('roles.managePermissions');
    Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');


    // Materias Primas
    Route::resource('raw-materials', RawMaterialController::class);

    // Proveedores
    Route::resource('suppliers', SupplierController::class);

    // Ordenes de Compra
    Route::resource('supplier-orders', SupplierOrderController::class);
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
