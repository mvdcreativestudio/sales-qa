<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PedidosYaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// WhatsApp Webhook
Route::get('/webhook', [WhatsAppController::class, 'webhook']);
Route::post('/webhook', [WhatsAppController::class, 'recibe']);

Route::post('/send-message', [WhatsAppController::class, 'send'])->name('api.send.messages');

// Pedidos Ya
Route::post('/pedidos-ya/estimate-order', [PedidosYaController::class, 'estimateOrder'])->name('api.pedidos-ya.estimate-order');
Route::post('/pedidos-ya/confirm-order', [PedidosYaController::class, 'confirmOrder'])->name('api.pedidos-ya.confirm-order');
