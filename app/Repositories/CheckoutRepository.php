<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\MercadoPagoAccount;
use App\Models\EcommerceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutStoreOrderRequest;
use Illuminate\Http\RedirectResponse;

class CheckoutRepository
{
    /**
     * Obtiene los datos para mostrar en la página de checkout.
     *
     * @return array
     */
    public function index(): array
    {
        $order = null;
        $cart = session('cart');
        $subtotal = 0;

        foreach ($cart as $item) {
            $price = !empty($item['price']) ? $item['price'] : $item['old_price'];
            $subtotal += $price * $item['quantity'];
        }

        $discount = session('coupon.discount', 0);
        $totalPedido = ($subtotal - $discount);
        $preferenceId = null;
        $settings = EcommerceSetting::first();
        $googleMapsApiKey = config('services.google.maps_api_key');

        return compact('order', 'cart', 'subtotal', 'totalPedido', 'preferenceId', 'discount', 'settings', 'googleMapsApiKey');
    }

    /**
     * Obtiene los datos para mostrar en la página de éxito.
     *
     * @param string $uuid
     * @return array
     */
    public function success(string $uuid): array
    {
        $order = Order::with('client')->where('uuid', $uuid)->firstOrFail();
        return compact('order');
    }

    /**
     * Obtiene los datos para mostrar en la página de fallo.
     *
     * @param string $uuid
     * @return array
     */
    public function failure(string $uuid): array
    {
        $order = Order::with('client')->where('uuid', $uuid)->firstOrFail();
        return compact('order');
    }

    /**
     * Procesa y almacena una nueva orden.
     *
     * @param CheckoutStoreOrderRequest $request
     * @param MercadoPagoService $mercadoPagoService
     * @return RedirectResponse
     */
    public function processOrder(CheckoutStoreOrderRequest $request, MercadoPagoService $mercadoPagoService): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $storeId = session('store.id');
            $clientData = $this->getClientData($request);
            $orderData = $this->getOrderData($request);

            // Guardar la orden y los datos del cliente
            $order = $this->createOrder($clientData, $orderData);

            if ($request->payment_method === 'card') {
                $redirectUrl = $this->processCardPayment($request, $order, $mercadoPagoService, $storeId);
                DB::commit();
                session()->forget('cart'); // Limpiar el carrito de compras
                return Redirect::away($redirectUrl);
            } else {
                // Lógica para pago en efectivo
                DB::commit();
                session()->forget('cart'); // Limpiar el carrito de compras

                Log::info('Pedido procesado correctamente.');
                // Redirigir al usuario a la página de éxito usando el UUID
                return redirect()->route('checkout.success', $order->uuid);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al procesar el pedido: {$e->getMessage()} en {$e->getFile()}:{$e->getLine()}");
            return back()->withErrors('Error al procesar el pedido. Por favor, intente nuevamente.')->withInput();
        }
    }

    /**
     * Crea una orden y un cliente en la base de datos.
     *
     * @param array $clientData
     * @param array $orderData
     * @return Order
     */
    public function createOrder(array $clientData, array $orderData): Order
    {
        // Crear y guardar el cliente
        $client = Client::updateOrCreate(
            ['email' => $clientData['email']],
            $clientData
        );

        Log::info('Cliente creado:', $client->toArray());

        // Crear la orden
        $order = Order::create(array_merge($orderData, ['client_id' => $client->id]));

        Log::info('Orden creada:', $order->toArray());

        return $order;
    }

    /**
     * Procesa el pago con tarjeta utilizando MercadoPago.
     *
     * @param Request $request
     * @param Order $order
     * @param MercadoPagoService $mercadoPagoService
     * @param int $storeId
     * @return string
     */
    private function processCardPayment(Request $request, Order $order, MercadoPagoService $mercadoPagoService, int $storeId): string
    {
        $cartItems = session('cart', []);
        $items = array_map(function ($item) {
            return [
                'title' => $item['name'],
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['price'] ?? $item['old_price'],
            ];
        }, $cartItems);

        // Obtener las credenciales de MercadoPago de la tienda
        $mercadoPagoAccount = MercadoPagoAccount::where('store_id', $storeId)->first();

        if (!$mercadoPagoAccount) {
            throw new \Exception('No se encontraron las credenciales de MercadoPago para la tienda asociada al pedido.');
        }

        Log::info('Credenciales de MercadoPago obtenidas:', $mercadoPagoAccount->toArray());

        // Configurar el SDK de MercadoPago con las credenciales de la tienda
        $mercadoPagoService->setCredentials($mercadoPagoAccount->public_key, $mercadoPagoAccount->access_token);

        $preferenceData = [
          'items' => $items,
          'payer' => ['email' => $request->email],
          'external_reference' => (string) $order->id,
          'discount' => [
              'amount' => session('coupon.discount', 0),
              'description' => 'Cupón de descuento aplicado'
          ]
        ];


        Log::info('Creando preferencia de pago con los siguientes datos:', $preferenceData);

        $preference = $mercadoPagoService->createPreference($preferenceData, $order);
        $order->preference_id = $preference->id;
        $order->save();

        Log::info('Preferencia de pago creada:', $preference->toArray());

        return "https://www.mercadopago.com.uy/checkout/v1/payment/redirect/?preference-id={$preference->id}";
    }

    /**
     * Aplica un cupón de descuento y lo almacena en la sesión.
     *
     * @param string $couponCode
     * @return array
     * @throws \Exception
     */
    public function applyCoupon(string $couponCode): array
    {
        $subtotal = session('subtotal', 0);

        if ($subtotal <= 0) {
            throw new \Exception('Parece que hay un error con el carrito. Por favor, intentalo nuevamente.');
        }

        return $this->applyCouponToSession($couponCode, $subtotal);
    }

    /**
     * Obtiene los datos del cliente desde la solicitud.
     *
     * @param Request $request
     * @return array
     */
    private function getClientData(Request $request): array
    {
        return [
            'name' => $request->name,
            'lastname' => $request->lastname,
            'type' => 'individual',
            'state' => 'Montevideo',
            'city' => 'Montevideo',
            'country' => 'Uruguay',
            'address' => $request->address ?? 'N/A',
            'phone' => $request->phone,
            'email' => $request->email,
        ];
    }

    /**
     * Obtiene los datos de la orden desde la solicitud.
     *
     * @param Request $request
     * @return array
     */
    private function getOrderData(Request $request): array
    {
        $subtotal = 0;
        $cartItems = session('cart', []);
        $products = [];

        foreach ($cartItems as $item) {
            $price = $item['price'] ?? $item['old_price'];
            $subtotal += $price * $item['quantity'];

            $flavors = [];
            if (!empty($item['flavors'])) {
                foreach ($item['flavors'] as $flavorId => $flavorInfo) {
                    $flavors[] = $flavorInfo['name'] . ' (x' . $flavorInfo['quantity'] . ')';
                }
            }

            $products[] = [
                'name' => $item['name'],
                'price' => $price,
                'quantity' => $item['quantity'],
                'flavors' => implode(', ', $flavors),
                'image' => $item['image'],
            ];
        }

        $costoEnvio = $request->shipping_cost;
        $total = $subtotal + $costoEnvio - session('coupon.discount', 0);

        $orderData = [
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'origin' => 'ecommerce',
            'store_id' => session('store.id', 1),
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => $costoEnvio,
            'total' => $total,
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'coupon_id' => session('coupon.id', null),
            'coupon_amount' => session('coupon.amount', 0),
            'discount' => session('coupon.discount', 0),
            'payment_method' => $request->payment_method,
            'shipping_method' => $request->shipping_method,
            'products' => json_encode($products),
        ];

        if ($request->filled('estimate_id')) {
            $orderData['estimate_id'] = $request->estimate_id;
        }

        return $orderData;
    }

    /**
     * Aplica un cupón a la sesión.
     *
     * @param string $couponCode
     * @param float $subtotal
     * @return array
     * @throws \Exception
     */
    public function applyCouponToSession(string $couponCode, float $subtotal): array
    {
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            throw new \Exception('El código del cupón no existe.');
        }

        if ($coupon->due_date != null && $coupon->due_date < now()) {
            throw new \Exception('El cupón ha expirado.');
        }

        $discount = $coupon->type === 'fixed' ? $coupon->amount : round($subtotal * ($coupon->amount / 100), 2);

        if ($discount <= 0) {
            throw new \Exception('No se pudo calcular un descuento válido.');
        }

        session([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'amount' => $coupon->amount,
                'discount' => $discount
            ]
        ]);

        return ['code' => $coupon->code, 'discount' => $discount];
    }
}
