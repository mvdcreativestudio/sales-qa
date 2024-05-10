<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Flavor;
use Illuminate\Support\Facades\Log;

class CartRepository
{
    // Añadir producto al carrito
    public function addProduct(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'El producto no existe.'];
        }

        $cart = session('cart', []);
        $flavorIds = $request->input('flavors', []);

        // Contar los sabores correctamente
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

        // Crear una clave única para identificar el producto en el carrito
        if (empty($flavors)) {
            $cartItemKey = $productId;
        } else {
            $cartItemKey = $productId . '-' . implode('-', $flavorIds);
        }

        // Añadir o actualizar el artículo en el carrito
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
                "old_price" => $product->old_price ?? 0,
                "price" => $product->price ?? $product->old_price ?? 0,
                "image" => $product->image,
                "flavors" => $flavors
            ];
        }

        session(['cart' => $cart]);
        $this->updateSubtotal();

        return ['success' => true, 'message' => 'Producto añadido al carrito con éxito!'];
    }

    // Actualizar la cantidad de un producto en el carrito
    public function updateProductQuantity($productId, $quantity)
    {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session(['cart' => $cart]);
            $this->updateSubtotal();

            return ['success' => true, 'message' => 'Carrito actualizado con éxito!'];
        }

        return ['success' => false, 'message' => 'Producto no encontrado en el carrito.'];
    }

    // Eliminar un artículo específico del carrito
    public function removeItem($cartItemKey)
    {
        $cart = session('cart', []);

        if (isset($cart[$cartItemKey])) {
            unset($cart[$cartItemKey]);
            session(['cart' => $cart]);
            $this->updateSubtotal();

            return ['status' => 'success', 'message' => 'Artículo eliminado del carrito con éxito.'];
        }

        return ['status' => 'error', 'message' => 'Artículo no encontrado en el carrito.'];
    }

    // Vaciar el carrito
    public function clearCart()
    {
        session()->forget('cart');
        session()->forget('subtotal');
    }

    // Limpiar toda la sesión
    public function clearSession()
    {
        session()->flush();
    }

    // Actualizar el subtotal del carrito
    private function updateSubtotal()
    {
        $cart = session('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $itemSubtotal;

            // Depuración de los subtotales
            Log::debug('Updating cart subtotal: Item', [
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'itemSubtotal' => $itemSubtotal
            ]);
        }

        session(['subtotal' => $subtotal]);
        Log::debug('Total Subtotal Updated', ['subtotal' => $subtotal]);
    }
}
