<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * El repositorio para las operaciones de pedidos.
     *
     * @var OrderRepository
    */
    protected $orderRepository;

    /**
     * Inyecta el repositorio en el controlador y los middleware.
     *
     * @param  OrderRepository  $orderRepository
    */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Muestra una lista de todos los pedidos.
     *
     * @return View
    */
    public function index(): View
    {
        $orders = $this->orderRepository->getAllOrders();
        return view('content.e-commerce.backoffice.orders.orders', compact('orders'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     *
     * @return View
    */
    public function create(): View
    {
        return view('content.e-commerce.backoffice.orders.add-order');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'payment_method' => 'required',
        ]);

        $clientData = $this->extractClientData($validatedData);
        $orderData = $this->prepareOrderData($validatedData['payment_method']);

        try {
            $order = $this->orderRepository->createOrder($clientData, $orderData, session('cart', []));
            session()->forget('cart');
            return redirect()->route('checkout.index')->with('success', 'Pedido realizado con éxito. ID de orden: ' . $order->id);
        } catch (\Exception $e) {
            return back()->withErrors('Error al procesar el pedido. Por favor, intente nuevamente.')->withInput();
        }
    }

    public function show(Order $order): View
    {
        $order = $this->orderRepository->loadOrderRelations($order);
        return view('content.e-commerce.backoffice.orders.show-order', compact('order'));
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->orderRepository->destroyOrder($id);
            return response()->json(['success' => true, 'message' => 'Pedido eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el pedido.'], 400);
        }
    }


    public function datatable()
    {
        return $this->orderRepository->getOrdersForDataTable();
    }

    public function orderProductsDatatable(Order $order)
    {
        return $this->orderRepository->getOrderProductsForDataTable($order);
    }

    private function extractClientData($validatedData)
    {
        return [
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'type' => 'individual',
            'state' => 'Montevideo',
            'country' => 'Uruguay',
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
        ];
    }

    private function prepareOrderData($paymentMethod)
    {
        $subtotal = array_reduce(session('cart', []), function ($carry, $item) {
            return $carry + ($item['price'] ?? $item['old_price']) * $item['quantity'];
        }, 0);

        return [
            'date' => now(),
            'time' => now()->format('H:i:s'),
            'origin' => 'ecommerce',
            'store_id' => 1,
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => session('costoEnvio', 0),
            'total' => $subtotal + session('costoEnvio', 0),
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => $paymentMethod,
            'shipping_method' => 'peya',
        ];
    }
}
