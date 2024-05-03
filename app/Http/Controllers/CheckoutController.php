<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Services\MercadoPagoService;
use MercadoPago\SDK;
use App\Models\MercadoPagoAccount;
use App\Models\Store;
use Illuminate\Support\Facades\Redirect;
use App\Models\Coupon;



use Log;

class CheckoutController extends Controller
{
    protected $orderRepository;
    protected $mercadoPagoService; // Declara una propiedad para el servicio de MercadoPago

    public function __construct(OrderRepository $orderRepository, MercadoPagoService $mercadoPagoService)
    {
        $this->orderRepository = $orderRepository;
        $this->mercadoPagoService = $mercadoPagoService; // Inyecta el servicio de MercadoPago

    }


    public function index()
    {
        $order = null;
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

        $discount = session('coupon.discount', 0);
        $totalPedido = ($subtotal - $discount) + $costoEnvio;

        $preferenceId = null;

        // Obtener configuraciones de ecommerce
        $settings = \DB::table('ecommerce_settings')->first();

        $googleMapsApiKey = config('services.google.maps_api_key');

        // Pasar el ID de la preferencia y otros datos a la vista junto con los settings
        return view('content.e-commerce.front.checkout', compact('order', 'cart', 'subtotal', 'costoEnvio', 'totalPedido', 'envioGratis', 'preferenceId', 'discount', 'settings', 'googleMapsApiKey'));
    }





  public function success($orderId)
  {
      // Cargar la orden con productos y sabores relacionados
      $order = Order::findOrFail($orderId);
      return view('content.e-commerce.front.checkout-success', compact('order'));
  }



  public function store(Request $request)
  {
      try {

          $storeId = session('store.id');

          // Validación de los datos recibidos
          $validatedData = $request->validate([
              'name' => 'required|max:255',
              'lastname' => 'required|max:255',
              'address' => 'required',
              'phone' => 'required',
              'email' => 'required|email',
              'payment_method' => 'required',
              'shipping_method' => 'required',
          ]);

          // Preparar datos del cliente
          $clientData = [
              'name' => $validatedData['name'],
              'lastname' => $validatedData['lastname'],
              'type' => 'individual',
              'state' => 'Montevideo',
              'city' => 'Montevideo',
              'country' => 'Uruguay',
              'address' => $validatedData['address'],
              'phone' => $validatedData['phone'],
              'email' => $validatedData['email'],
          ];

          // Preparar datos de la orden
          $subtotal = 0;
          $cartItems = session('cart', []);
          $products = [];

          foreach ($cartItems as $item) {
              $price = $item['price'] ?? $item['old_price'];
              $subtotal += $price * $item['quantity'];

              $flavors = [];
              if (!empty($item['flavors'])) {
                  foreach ($item['flavors'] as $flavorId => $flavorInfo) {
                      $flavors[] = $flavorInfo['name'] . ' (' . $flavorInfo['quantity'] . 'x)';
                  }
              }

              $products[] = [
                  'name' => $item['name'],
                  'price' => $price,
                  'quantity' => $item['quantity'],
                  'flavors' => implode(', ', $flavors),
              ];
          }

          $costoEnvio = 60; // Define costos de envío predeterminados
          $total = $subtotal + $costoEnvio;

          $orderData = [
              'date' => now(),
              'time' => now()->format('H:i:s'),
              'origin' => 'ecommerce',
              'store_id' => session('store.id', 1), // Default to store ID 1 if not set in session
              'subtotal' => $subtotal,
              'tax' => 0,
              'shipping' => $costoEnvio,
              'total' => $total,
              'payment_status' => 'pending',
              'shipping_status' => 'pending',
              'payment_method' => $validatedData['payment_method'],
              'shipping_method' => $validatedData['shipping_method'],
              'products' => json_encode($products),
          ];

          // Guardar la orden y los datos del cliente
          DB::beginTransaction();
          $order = $this->orderRepository->createOrder($clientData, $orderData);
          DB::commit();

          Log::info('Orden creada:', ['order_id' => $order->id, 'order_data' => $orderData]);

        if ($validatedData['payment_method'] === 'card') {
            // Lógica para MercadoPago
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
            $this->mercadoPagoService->setCredentials($mercadoPagoAccount->public_key, $mercadoPagoAccount->access_token);

            $preferenceData = [
                'items' => $items,
                'payer' => ['email' => $clientData['email']],
            ];

            Log::info('Creando preferencia de pago con los siguientes datos:', $preferenceData);

            $preference = $this->mercadoPagoService->createPreference($preferenceData, $order);
            $preferenceId = $preference->id;
            $order->preference_id = $preferenceId;
            $order->save();

            Log::info('Preferencia de pago creada:', $preference->toArray());

            DB::commit();
            session()->forget('cart'); // Limpiar el carrito de compras

            // Redirigir al usuario a la página de pago de MercadoPago
            $redirectUrl = "https://www.mercadopago.com.uy/checkout/v1/payment/redirect/?preference-id=$preferenceId";
            return Redirect::away($redirectUrl);
        } else {
            // Lógica para pago en efectivo
            DB::commit();
            session()->forget('cart'); // Limpiar el carrito de compras

            Log::info('Pedido procesado correctamente.');

            // Redirigir al usuario a la página de éxito
            return redirect()->route('checkout.success', $order->id);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error al procesar el pedido: {$e->getMessage()} en {$e->getFile()}:{$e->getLine()}");
        return back()->withErrors('Error al procesar el pedido. Por favor, intente nuevamente.')->withInput();
    }
}

public function applyCoupon(Request $request)
{
    try {
        $validatedData = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $couponCode = $validatedData['coupon_code'];
        Log::info('Applying coupon', ['coupon_code' => $couponCode]);

        $coupon = Coupon::where('code', $couponCode)->first();

        // Verifica si el cupón existe
        if (!$coupon) {
            Log::error('Coupon not found', ['coupon_code' => $couponCode]);
            return back()->with('error', 'El código del cupón no existe.');
        }

        // Verifica si el cupón ha expirado
        if ($coupon->due_date != null && $coupon->due_date < now()) {
            Log::error('Coupon expired', ['coupon_code' => $couponCode, 'due_date' => $coupon->due_date]);
            return back()->with('error', 'El cupón ha expirado.');
        }

        // Asegura que hay un subtotal con el que trabajar
        $subtotal = session('subtotal', 0);
        if ($subtotal <= 0) {
            Log::error('Invalid subtotal', ['subtotal' => $subtotal]);
            return back()->with('error', 'Parece que hay un error con el carrito. Por favor, intentalo nuevamente');
        }

        // Calcula el descuento basado en el tipo de cupón
        $discount = $coupon->type === 'fixed' ? $coupon->amount : round($subtotal * ($coupon->amount / 100), 2);
        if ($discount <= 0) {
            Log::error('Failed to calculate a valid discount', ['coupon_type' => $coupon->type, 'coupon_amount' => $coupon->amount, 'subtotal' => $subtotal]);
            return back()->with('error', 'No se pudo calcular el descuento. Por favor, intentelo nuevamente.');
        }

        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount
            ]
        ]);

        Log::info('Coupon applied successfully', ['coupon_code' => $coupon->code, 'discount' => $discount]);
        Log::info('Session data after applying coupon', ['session_data' => session()->all()]);

        return back()->with('success', 'El cupón "' . $coupon->code . '" se ha aplicado correctamente.');
    } catch (\Exception $e) {
        Log::error('Error applying coupon', ['coupon_code' => $request->input('coupon_code', 'N/A'), 'error' => $e->getMessage()]);
        return back()->with('error', 'Ocurrió un error al aplicar el cupón. Por favor, intente nuevamente.');
    }
}










}
