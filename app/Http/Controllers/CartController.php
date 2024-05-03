<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\Flavor;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        return view('content.e-commerce.front.cart');
    }

    public function selectStore(Request $request)
    {
        $store = Store::find($request->input('storeId'));
        if (!$store) {
            return Redirect::back()->with('error', 'La tienda no existe.');
        }
        session(['store' => ['id' => $store->id, 'name' => $store->name, 'address' => $store->address]]);
        return Redirect::route('store', ['storeId' => $store->id]);
    }

    public function addToCart(Request $request, $productId)
  {
      $product = Product::find($productId);
      if (!$product) {
          return Redirect::back()->with('error', 'El producto no existe.');
      }

      $cart = session('cart', []);
      $flavorIds = $request->input('flavors', []);

      // Asegurarse de contar sabores duplicados correctamente
      $flavors = [];
      foreach ($flavorIds as $flavorId) {
          $flavor = Flavor::find($flavorId);
          if ($flavor) {
              if (!isset($flavors[$flavorId])) {
                  $flavors[$flavorId] = ['name' => $flavor->name, 'quantity' => 1];
              } else {
                  $flavors[$flavorId]['quantity'] += 1;
              }
          }
      }

      if (empty($flavors)) {
          $cartItemKey = $productId;
      } else {
          $cartItemKey = $productId . '-' . implode('-', $flavorIds);
      }

      if (isset($cart[$cartItemKey])) {
          $cart[$cartItemKey]['quantity'] += 1;
          foreach ($flavors as $id => $details) {
              if (isset($cart[$cartItemKey]['flavors'][$id])) {
                  $cart[$cartItemKey]['flavors'][$id]['quantity'] += $details['quantity'];
              } else {
                  $cart[$cartItemKey]['flavors'][$id] = $details;
              }
          }
      } else {
          $cart[$cartItemKey] = [
              "id" => $product->id,
              "name" => $product->name,
              "sku" => $product->sku,
              "description" => $product->description,
              "type" => $product->type,
              "quantity" => 1,
              "old_price" => $product->old_price ?? 0,  // Default to 0 if not set
              "price" => $product->price ?? $product->old_price ?? 0,  // Use old_price if price is not set
              "image" => $product->image,
              "flavors" => $flavors
          ];
      }

      session(['cart' => $cart]);
      $this->updateSubtotal();

      return Redirect::back()->with('success', 'Producto añadido al carrito con éxito!');
    }


    public function updateCart(Request $request)
    {
        $productId = $request->id;
        $quantity = $request->quantity;

        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session(['cart' => $cart]);
            $this->updateSubtotal();
            return Redirect::back()->with('success', 'Carrito actualizado con éxito!');
        }

        return Redirect::back()->with('error', 'Producto no encontrado en el carrito.');
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->id;
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
            $this->updateSubtotal();
            return Redirect::back()->with('success', 'Producto eliminado del carrito con éxito!');
        }

        return Redirect::back()->with('error', 'Producto no encontrado en el carrito.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        session()->forget('subtotal');
        return Redirect::back()->with('success', 'Carrito vaciado con éxito.');
    }

    public function clearSession()
    {
        session()->flush();
        return Redirect::to('/')->with('success', 'Sesión limpiada correctamente.');
    }

    private function updateSubtotal() {
      $cart = session('cart', []);
      $subtotal = 0;

      foreach ($cart as $item) {
          $itemSubtotal = $item['price'] * $item['quantity'];
          $subtotal += $itemSubtotal;
          // Depuración para ver los subtotales de cada item
          Log::debug('Updating cart subtotal: Item Price', ['price' => $item['price']]);
          Log::debug('Updating cart subtotal: Item Quantity', ['quantity' => $item['quantity']]);
          Log::debug('Updating cart subtotal: Item Subtotal', ['itemSubtotal' => $itemSubtotal]);
      }

      session(['subtotal' => $subtotal]);
      Log::debug('Total Subtotal Updated', ['subtotal' => $subtotal]);
  }


}
