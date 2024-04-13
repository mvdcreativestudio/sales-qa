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

        // Pasar el ID de la preferencia y otros datos a la vista
        return view('content.e-commerce.front.checkout', compact('order', 'cart', 'subtotal', 'costoEnvio', 'totalPedido', 'envioGratis', 'preferenceId', 'discount'));
    }




  public function success($orderId)
  {
      // Cargar la orden con productos y sabores relacionados
      $order = Order::with(['products.flavors'])->findOrFail($orderId);
      return view('content.e-commerce.front.checkout-success', compact('order'));
  }



  public function store(Request $request)
{
    try {
        // Obtener el ID de la tienda de la sesión
        $storeId = session('store.id');

        // Validación de los datos recibidos
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'payment_method' => 'required',
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
        if (!is_array($cartItems)) {
            $cartItems = [];
        }

        foreach ($cartItems as $item) {
            $price = $item['price'] ?? $item['old_price'];
            $subtotal += $price * ($item['quantity'] ?? 1);
        }

        $costoEnvio = session('costoEnvio', 60); // Costo de envío predeterminado si no se ha establecido en la sesión
        $total = $subtotal + $costoEnvio;

        $orderData = [
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'origin' => 'ecommerce',
            'store_id' => $storeId,
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => $costoEnvio,
            'total' => $total,
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => $validatedData['payment_method'],
            'shipping_method' => 'peya',
        ];

        Log::info('Datos validados y preparados para la orden y el cliente:', [
            'client_data' => $clientData,
            'order_data' => $orderData
        ]);

        DB::beginTransaction();
        $order = $this->orderRepository->createOrder($clientData, $orderData, $cartItems);

        Log::info('Orden creada:', $order->toArray());

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
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        Log::info('Applying coupon', ['coupon_code' => $request->coupon_code]);

        $coupon = Coupon::where('code', $request->coupon_code)
                        ->where(function ($query) {
                            $query->whereDate('due_date', '>=', now())
                                  ->orWhereNull('due_date');
                        })
                        ->first();

        if (!$coupon) {
            Log::error('Coupon validation failed', ['coupon_code' => $request->coupon_code]);
            return back()->withErrors('Invalid or expired coupon code. Please try again.');
        }

        // Ensure there is a subtotal to work with
        $subtotal = session('subtotal', 0);

        if ($subtotal <= 0) {
            Log::error('Subtotal for coupon calculation is not valid', ['subtotal' => $subtotal]);
            return back()->withErrors('There seems to be an issue with the cart subtotal. Please check your cart and try again.');
        }

        // Calculating discount based on the type of the coupon
        $discount = $coupon->type === 'fixed' ? $coupon->amount : round($subtotal * ($coupon->amount / 100), 2);

        if ($discount <= 0) {
            Log::error('Failed to calculate a valid discount', ['coupon_type' => $coupon->type, 'coupon_amount' => $coupon->amount, 'subtotal' => $subtotal]);
            return back()->withErrors('Failed to calculate the discount. Please check the coupon details and try again.');
        }

        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount
            ]
        ]);

        Log::info('Coupon applied successfully', ['coupon_code' => $coupon->code, 'discount' => $discount]);
        Log::info('Session data after applying coupon', ['session_data' => session()->all()]);

        return back()->with('success', 'El cupón ' . $coupon->code . ' se ha aplicado correctamente.');
    } catch (\Exception $e) {
        Log::error('Error applying coupon', ['coupon_code' => $request->input('coupon_code', 'N/A'), 'error' => $e->getMessage()]);
        return back()->withErrors('An error occurred while applying the coupon. Please try again.');
    }
}








}
