<?php

namespace App\Repositories;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PedidosYaRepository
{
  /**
   * Calcula el costo de envío de un pedido y si es posible realizarlo.
   *
   * @param  Request  $request
   * @return array
  */
  public function estimateOrderRequest(Request $request): array {
    $url = 'https://courier-api.pedidosya.com/v3/shippings/estimates';
    $apiKey = config('services.pedidosya.api_key');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->post($url, $request->all());

    return $response->json();
  }

  /**
    * Confirma una estimación de pedido y lo envía a la API de PedidosYa.
    *
    * @param  Request  $request
    * @return array
  */
  public function confirmOrderRequest(Request $request): array
  {
      $url = 'https://courier-api.pedidosya.com/v3/shippings/estimates/' . $request->estimate_id . '/confirm';
      $apiKey = config('services.pedidosya.api_key');

      $response = Http::withHeaders([
          'Authorization' => 'Bearer ' . $apiKey,
          'Content-Type' => 'application/json',
      ])->post($url, $request->all());

      return $response->json();
  }
}
