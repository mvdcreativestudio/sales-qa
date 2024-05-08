<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PedidosYaRepository;
use Illuminate\Http\JsonResponse;

class PedidosYaController extends Controller
{
  /**
   * El repositorio para las operaciones de PedidosYa.
   *
   * @var PedidosYaRepository
  */
  protected $pedidosYaRepository;

  /**
   * Inyecta el repositorio en el controlador.
   *
   * @param  PedidosYaRepository  $pedidosYaRepository
  */
  public function __construct(PedidosYaRepository $pedidosYaRepository)
  {
      $this->pedidosYaRepository = $pedidosYaRepository;
  }

  /**
   * Calcula el costo de envío de un pedido y si es posible realizarlo.
   *
   * @param  Request  $request
   * @return JsonResponse
  */
  public function estimateOrder(Request $request): JsonResponse
  {
    $estimatedOrderResponse = $this->pedidosYaRepository->estimateOrderRequest($request);

    return response()->json($estimatedOrderResponse)
      ->header('Content-Type', 'application/json')
      ->header('Access-Control-Allow-Origin', '*');
  }

  /**
    * Confirma una estimación de pedido y lo envía a la API de PedidosYa.
    *
    * @param  Request  $request
    * @return JsonResponse
  */
  public function confirmOrder(Request $request): JsonResponse
  {
    $confirmedOrderResponse = $this->pedidosYaRepository->confirmOrderRequest($request);

    return response()->json($confirmedOrderResponse)
      ->header('Content-Type', 'application/json')
      ->header('Access-Control-Allow-Origin', '*');
  }
}
