<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Log;

class CheckoutController extends Controller
{
  public function index() {
    $cart = session('cart', []);
    $subtotal = 0;
    $costoEnvio = 60;

    foreach ($cart as $item) {
        $price = !empty($item['price']) ? $item['price'] : $item['old_price'];
        $subtotal += $price * $item['quantity'];
    }

    $envioGratis = $subtotal > 100; // Envío gratis para pedidos mayores a $100
    if ($envioGratis) {
        $costoEnvio = 0;
    }

    $totalPedido = $subtotal + $costoEnvio;

    return view('content.e-commerce.front.checkout', compact('cart', 'subtotal', 'costoEnvio', 'totalPedido', 'envioGratis'));
  }


  public function store(Request $request)
{
    // Validación de los datos recibidos
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'lastname' => 'required|max:255',
        'address' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'payment_method' => 'required',
    ]);

    DB::beginTransaction();

    try {
        Log::info('Creando cliente...');
        $client = Client::create([
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'type' => 'individual',
            'state' => 'Montevideo',
            'country' => 'Uruguay',
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
        ]);
        Log::info('Cliente creado con éxito. ID: ' . $client->id);

        // Calcular el total del carrito
        $subtotal = 0;

        foreach (session('cart') as $item) {
            $price = $item['price'] != null ? $item['price'] : $item['old_price'];
            $subtotal += $price * $item['quantity'];
        }

        // Calcular el total de la orden
        $total = 0;

        if (session('cart')['coupon']) {
            $total = $subtotal - session('cart')['coupon']['discount_value'] + session('costoEnvio');
        } else {
            $total = $subtotal + session('costoEnvio');
        }


        $orderData = [
            'date' => now(),
            'origin' => 'ecommerce',
            'client_id' => $client->id,
            'store_id' => 1, // Asumiendo que este ID es correcto
            'products' => json_encode(session('cart')),
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => session('costoEnvio') ?? 0,
            'coupon_id' => session('cart')['coupon']['id'] ?? null,
            'coupon_amount' => session('cart')['coupon']['discount_value'] ?? null,
            'discount' => 0,
            'total' => $total,
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => $validatedData['payment_method'],
            'shipping_method' => 'peya',
        ];

        Log::info('Intentando crear orden para el cliente ID: ' . $client->id);

        $order = new Order($orderData);
        $order->save();

        Log::info('Orden creada con éxito. ID: ' . $order->id);

        DB::commit();

        session()->forget('cart');

        return redirect()->route('checkout.success')->with('success', 'Pedido realizado con éxito.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error al procesar el pedido: {$e->getMessage()} en {$e->getFile()}:{$e->getLine()}");
        return back()->withErrors('Error al procesar el pedido. Por favor, intente nuevamente.')->withInput();
    }
}

}
