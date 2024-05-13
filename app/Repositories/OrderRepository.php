<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Client;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OrderRepository
{
  /**
   * Obtiene todos los pedidos.
   *
   * @return Collection
  */
  public function getAllOrders(): Collection
  {
    return Order::all();
  }

  /**
   * Almacena un nuevo pedido en la base de datos.
   *
   * @param  StoreOrderRequest  $request
   * @return Order
  */
  public function store($request)
  {
    $clientData = $this->extractClientData($request->validated());
    $orderData = $this->prepareOrderData($request->payment_method);

    DB::beginTransaction();

    try {
        $client = Client::firstOrCreate(['email' => $clientData['email']], $clientData);
        $order = new Order($orderData);
        $order->client()->associate($client);

        $order->save();

        $products = json_decode($orderData['products'], true);
        $order->products = $products;

        $order->save();

        DB::commit();

        session()->forget('cart');

        return $order;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
  }

  /**
   * Prepar los datos del cliente para ser almacenados en la base de datos.
   *
   * @param array $validatedData
   * @return array
   */
  private function extractClientData(array $validatedData): array
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

  /**
   * Prepara los datos del pedido para ser almacenados en la base de datos.
   *
   * @param string $paymentMethod
   * @return array
  */
  private function prepareOrderData(string $paymentMethod): array
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

  /**
   * Carga las relaciones de un pedido.
   *
   * @param Order $order
   * @return Order
  */
  public function loadOrderRelations(Order $order): Order
  {
    return $order->load('client', 'products');
  }

  /**
   * Elimina un pedido específico.
   *
   * @param int $orderId
   * @return void
  */
  public function destroyOrder($orderId): void
  {
    $order = Order::findOrFail($orderId);
    $order->delete();
  }

  /**
   * Obtiene los pedidos para la DataTable.
   *
   * @return mixed
  */
  public function getOrdersForDataTable(): mixed
  {
    $query = Order::select([
                'orders.id',
                'orders.date',
                'orders.time',
                'orders.client_id',
                'orders.store_id',
                'orders.subtotal',
                'orders.tax',
                'orders.shipping',
                'orders.coupon_id',
                'orders.coupon_amount',
                'orders.discount',
                'orders.total',
                'orders.products',
                'orders.payment_status',
                'orders.shipping_status',
                'orders.payment_method',
                'orders.shipping_method',
                'orders.shipping_tracking',
                'clients.email as client_email',
                'stores.name as store_name',
                DB::raw("CONCAT(clients.name, ' ', clients.lastname) as client_name")
              ])
            ->join('clients', 'orders.client_id', '=', 'clients.id')
            ->join('stores', 'orders.store_id', '=', 'stores.id');


    return DataTables::of($query)->make(true);
  }

  /**
   * Obtiene los productos de un pedido para la DataTable.
   *
   * @param Order $order
   * @return mixed
  */
  public function getOrderProductsForDataTable(Order $order)
  {
      $query = OrderProduct::where('order_id', $order->id)
          ->with([
              'product.categories:id,name',
              'product.store:id,name',
              'product.flavors:id,name'
          ])
          ->select(['id', 'product_id', 'quantity', 'price']);

      return DataTables::of($query)
          ->addColumn('product_name', function ($orderProduct) {
              $productName = $orderProduct->product->name;
              $flavors = $orderProduct->product->flavors->pluck('name')->implode(', ');
              return $flavors ? $productName . "<br><small>$flavors</small>" : $productName;
          })
          ->addColumn('category', function ($orderProduct) {
              return $orderProduct->product->categories->implode('name', ', ');
          })
          ->addColumn('store_name', function ($orderProduct) {
              return $orderProduct->product->store->name;
          })
          ->addColumn('total_product', function ($orderProduct) {
              return number_format($orderProduct->quantity * $orderProduct->price, 2);
          })
          ->rawColumns(['product_name'])  // Indica a DataTables que no escape HTML en la columna 'product_name'
          ->make(true);
  }
}
