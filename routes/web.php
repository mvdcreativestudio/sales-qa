<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;

use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\OmnichannelController;

Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return view('content.dashboard.dashboard-mvd');
    })->name('dashboard');

    Route::resource('raw-materials', RawMaterialController::class); // Genero automaticamente las rutas para el CRUD de Raw Materials
});

// Omnicanalidad
Route::get('omnichannel', [OmnichannelController::class, 'index'])->name('omnichannel');

// E-Commerce
Route::get('shop', [EcommerceController::class, 'index'])->name('shop');
Route::get('store', [EcommerceController::class, 'store'])->name('store');
Route::get('checkout', [EcommerceController::class, 'checkout'])->name('checkout');
