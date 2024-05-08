<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CheckoutRepository
{
    public function createOrder(array $clientData, array $orderData)
    {
        // Crear y guardar el cliente
        $client = Client::updateOrCreate(
            ['email' => $clientData['email']],
            $clientData
        );

        // Crear la orden
        $order = Order::create(array_merge($orderData, ['client_id' => $client->id]));

        return $order;
    }

    public function applyCouponToSession($couponCode, $subtotal)
    {
        // Encuentra el cupón por su código
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            throw new \Exception('El código del cupón no existe.');
        }

        // Verifica la fecha de expiración
        if ($coupon->due_date != null && $coupon->due_date < now()) {
            throw new \Exception('El cupón ha expirado.');
        }

        // Calcula el descuento según el tipo
        $discount = $coupon->type === 'fixed' ? $coupon->amount : round($subtotal * ($coupon->amount / 100), 2);

        if ($discount <= 0) {
            throw new \Exception('No se pudo calcular un descuento válido.');
        }

        // Guarda el cupón en la sesión
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount
            ]
        ]);

        return ['code' => $coupon->code, 'discount' => $discount];
    }
}
