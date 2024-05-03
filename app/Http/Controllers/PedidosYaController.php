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
      $apiKey = '6734-290238-d2faf4c0-68cc-404e-59af-23099503d167';

      $response = Http::withHeaders([
          'Authorization' => 'Bearer ' . $apiKey,
          'Content-Type' => 'application/json',
      ])->post($url, $request->all());

      return response($response->body())
              ->header('Content-Type', 'application/json')
              ->header('Access-Control-Allow-Origin', '*');
  }
}
