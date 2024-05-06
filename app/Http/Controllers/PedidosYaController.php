<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class PedidosYaController extends Controller
{
  public function estimateOrder(Request $request): Response
  {
      $url = 'https://courier-api.pedidosya.com/v3/shippings/estimates';
      $apiKey = config('services.pedidosya.api_key');

      $response = Http::withHeaders([
          'Authorization' => 'Bearer ' . $apiKey,
          'Content-Type' => 'application/json',
      ])->post($url, $request->all());

      return response($response->body())
              ->header('Content-Type', 'application/json')
              ->header('Access-Control-Allow-Origin', '*');
  }
}
