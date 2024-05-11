<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MercadoPagoRepository
{
    public function updatePaymentStatus($orderId, $status)
    {
        // Encuentra la orden por su ID
        $order = Order::find($orderId);

        if ($order) {
            $order->payment_status = $status;
            $order->save();

            Log::info("Estado de la orden actualizado a '$status' para la orden con ID: $orderId");

            return true;
        } else {
            Log::error("No se encontró la orden con ID: $orderId");
        }

        return false;
    }
}
