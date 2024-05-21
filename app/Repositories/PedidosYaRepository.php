<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

      try {
        // Deshabilita el SSL Verify Host y Verify Peer y el debug habilitado

          $response = Http::withHeaders([
              'Authorization' => 'Bearer ' . $apiKey,
              'Content-Type' => 'application/json',
              'User-Agent' => 'PedidosYa MVD Studio Client'
          ])->post($url);

          if ($response->successful()) {
              return $response->json();
          } else {
              Log::error('PedidosYa API error', [
                  'url' => $url,
                  'response' => $response->body(),
                  'status' => $response->status()
              ]);
              return [
                  'error' => 'API request failed',
                  'status' => $response->status(),
                  'body' => $response->body()
              ];
          }
      } catch (\Exception $e) {
          Log::error('PedidosYa API exception', [
              'url' => $url,
              'message' => $e->getMessage()
          ]);
          return [
              'error' => 'Exception occurred',
              'message' => $e->getMessage()
          ];
      }
  }


}
