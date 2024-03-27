<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all();
        return view('content.e-commerce.backoffice.orders.orders', compact('orders'));
    }

    public function create()
    {
        return view('content.e-commerce.backoffice.orders.add-order');
    }

    public function store()
    {
        $order = new Order;

        $order->date = request('date');
        $order->origin = request('origin');
        $order->client_id = request('client_id');
        $order->store_id = request('store_id');
        $order->products = request('products');
        $order->subtotal = request('subtotal');
        $order->tax = request('tax');
        $order->shipping = request('shipping');
        $order->coupon_id = request('coupon_id');
        $order->coupon_amount = request('coupon_amount');
        $order->discount = request('discount');
        $order->total = request('total');
        $order->payment_status = request('payment_status');
        $order->shipping_status = request('shipping_status');
        $order->payment_method = request('payment_method');
        $order->shipping_method = request('shipping_method');
        $order->shipping_tracking = request('shipping_tracking');
        $order->save();

        // Asociar productos, si existen en la petición
        if (isset($validatedData['products'])) {
          foreach ($validatedData['products'] as $productData) {
              $order->products()->attach($productData['id'], [
                  'quantity' => $productData['quantity'],
                  'price' => $productData['price']
              ]);
          }
      }

      return redirect('/orders')->with('success', 'Order created successfully.');
    }

    public function edit()
    {
        return view('orders.edit');
    }


    public function delete()
    {
        $order = Order::find(request('id'));
        $order->delete();

        return redirect('/orders');
    }
}
