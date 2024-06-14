<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderRequest;

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
      $this->middleware(['check_permission:access_orders', 'user_has_store'])->only(
          [
              'index',
              'create',
              'show',
              'destroy',
              'datatable',
              'orderProductsDatatable'
          ]
      );
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

  /**
   * Almacena un nuevo pedido en la base de datos.
   *
   * @param  StoreOrderRequest  $request
   * @return RedirectResponse
  */
  public function store(StoreOrderRequest $request): RedirectResponse
  {
      try {
          $order = $this->orderRepository->store($request);
          return redirect()->route('checkout.index')->with('success', 'Pedido realizado con éxito. ID de orden: ' . $order->id);
      } catch (\Exception $e) {
          return back()->withErrors('Error al procesar el pedido. Por favor, intente nuevamente.')->withInput();
      }
  }

  /**
   * Muestra un pedido específico.
   *
   * @param Order $order
   * @return View
  */
  public function show(Order $order): View
  {
      $order = $this->orderRepository->loadOrderRelations($order);
      $products = json_decode($order->products, true);
      $clientOrdersCount = $this->orderRepository->getClientOrdersCount($order->client_id);

      return view('content.e-commerce.backoffice.orders.show-order', compact('order', 'products', 'clientOrdersCount'));
  }


  /**
   * Eliminar un pedido específico.
   *
   * @param int $id
   * @return JsonResponse
  */
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

  /**
   * Obtiene los pedidos para la DataTable.
   *
   * @return mixed
  */
  public function datatable(): mixed
  {
      return $this->orderRepository->getOrdersForDataTable();
  }

  /**
   * Obtiene los productos de un pedido para la DataTable.
   *
   * @param Order $order
   * @return mixed
  */
  public function orderProductsDatatable(Order $order)
  {
      return $this->orderRepository->getOrderProductsForDataTable($order);
  }
}
