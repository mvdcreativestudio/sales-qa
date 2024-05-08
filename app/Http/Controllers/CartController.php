<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\CartRepository;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\SelectStoreRequest;

class CartController extends Controller
{
    /**
     * El repositorio para la gestión del carrito de compras.
     *
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @param CartRepository $cartRepository
     */

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    /**
     * Muestra la página del carrito de compras.
     * @return View
     */
    public function index(): View
    {
        return view('content.e-commerce.front.cart');
    }



    public function selectStore(SelectStoreRequest $request)
    {
      // Limpiar la sesión
      $request->session()->flush();

      // Encuentra la tienda usando el `storeId` validado
      $storeId = $request->input('storeId');
      $store = Store::find($storeId);

      // Esto no debería ser necesario con la validación de `exists`, pero es una comprobación adicional
      if (!$store) {
          return Redirect::back()->with('error', 'La tienda no existe.');
      }

      // Guardar la tienda seleccionada en la sesión
      $request->session()->put('store', [
          'id' => $store->id,
          'name' => $store->name,
          'address' => $store->address
      ]);

      // Redirigir a la página de la tienda seleccionada
      return Redirect::route('store', ['storeId' => $store->id]);
    }



    /**
     * Añadir un producto al carrito.
     *
     * @param AddToCartRequest $request
     */

    public function addToCart(AddToCartRequest $request, $productId)
    {

        $result = $this->cartRepository->addProduct($request, $productId);

        if ($result['success']) {
            return Redirect::back()->with('success', $result['message']);
        } else {
            return Redirect::back()->with('error', $result['message']);
        }

    }


    /**
     * Actualizar la cantidad de un producto en el carrito.
     *
     * @param UpdateCartRequest $request
     */

    public function updateCart(UpdateCartRequest $request)
    {
      $productId = $request->input('id');
      $quantity = $request->input('quantity');

      $result = $this->cartRepository->updateProductQuantity($productId, $quantity);

      if ($result['success']) {
          return Redirect::back()->with('success', $result['message']);
      } else {
          return Redirect::back()->with('error', $result['message']);
      }
    }


    /**
     * Eliminar un producto del carrito.
     *
     * @param Request $request
     */

    public function removeItem(Request $request)
    {
        $result = $this->cartRepository->removeItem($request->input('key'));

        return Redirect::back()->with($result['status'], $result['message']);
    }


    /**
     * Limpiar el carrito.
     *
     * @return Redirect
     */

    public function clearCart()
    {
        $this->cartRepository->clearCart();
        return Redirect::back()->with('success', 'Carrito vaciado con éxito.');
    }


    /**
     * Limpiar toda la sesión.
     *
     * @return Redirect
     */

    public function clearSession()
    {
        $this->cartRepository->clearSession();
        return Redirect::to('/')->with('success', 'Sesión limpiada correctamente.');
    }
}
