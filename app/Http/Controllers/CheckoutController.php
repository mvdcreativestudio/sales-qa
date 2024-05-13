<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Services\MercadoPagoService;
use App\Repositories\CheckoutRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Requests\CheckoutStoreOrderRequest;
use App\Http\Requests\ApplyCouponRequest;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
  /**
   * El repositorio de checkout para la gestión de la finalización de compras.
   *
   * @var CheckoutRepository
  */
  protected $checkoutRepository;

  /**
   * El repositorio de pedidos para la gestión de pedidos.
   *
   * @var OrderRepository
  */
  protected $orderRepository;

  /**
   * El servicio de MercadoPago para la gestión de pagos.
   *
   * @var MercadoPagoService
  */
  protected $mercadoPagoService;

  /**
   * Inyecta el repositorio y el servicio en el controlador.
   *
   * @param  CheckoutRepository  $checkoutRepository
   * @param  OrderRepository  $orderRepository
   * @param  MercadoPagoService  $mercadoPagoService
  */
  public function __construct(CheckoutRepository $checkoutRepository, OrderRepository $orderRepository, MercadoPagoService $mercadoPagoService)
  {
    $this->middleware(['ensure_store_selected', 'ensure_cart_not_empty'])->only('index');

    $this->checkoutRepository = $checkoutRepository;
    $this->orderRepository = $orderRepository;
    $this->mercadoPagoService = $mercadoPagoService;
  }

  /**
   * Muestra la página de checkout.
   *
   * @return View
  */
  public function index(): View
  {
    $checkout = $this->checkoutRepository->index();
    return view('content.e-commerce.front.checkout', $checkout);
  }

  /**
   * Muestra la página de éxito de la compra.
   *
   * @param  int  $orderId
   * @return View
  */
  public function success(int $orderId): View
  {
    $order = $this->checkoutRepository->success($orderId);
    return view('content.e-commerce.front.checkout-success', $order);
  }

    /**
   * Muestra la página de fallo de la compra.
   *
   * @param  int  $orderId
   * @return View
  */
  public function failure(int $orderId): View
  {
    $order = $this->checkoutRepository->failure($orderId);
    return view('content.e-commerce.front.checkout-failure', $order);
  }

  /**
   * Almacena una nueva orden en la base de datos.
   *
   * @param  CheckoutStoreOrderRequest  $request
   * @return RedirectResponse
  */
  public function store(CheckoutStoreOrderRequest $request): RedirectResponse
  {
    return $this->checkoutRepository->processOrder($request, $this->mercadoPagoService);
  }

  /**
   * Aplica un cupón de descuento.
   *
   * @param  ApplyCouponRequest  $request
   * @return RedirectResponse
  */
  public function applyCoupon(ApplyCouponRequest $request): RedirectResponse
  {
    try {
        $couponData = $this->checkoutRepository->applyCouponToSession($request->coupon_code);
        return back()->with('success', 'El cupón "' . $couponData['code'] . '" se ha aplicado correctamente.');
    } catch (\Exception $e) {
        Log::error('Error applying coupon', ['coupon_code' => $request->coupon_code, 'error' => $e->getMessage()]);
        return back()->with('error', $e->getMessage());
    }
  }
}
