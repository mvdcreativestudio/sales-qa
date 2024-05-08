<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{

    public function __construct()
    {
        // Configurar el acceso a la API de MercadoPago
        SDK::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function createPreference($preferenceData, $order)
    {
        $preference = new Preference();

        // Configurar el pagador como un objeto
        $payer = new \stdClass();
        $payer->email = $preferenceData['payer']['email'];
        $preference->payer = $payer;
        $preference->currency_id = 'UYU';

        // Configurar los ítems
        $items = [];
        foreach ($preferenceData['items'] as $itemData) {
            $item = new Item();
            $item->title = $itemData['title'];
            $item->description = 'descripcion';
            $item->quantity = $itemData['quantity'];
            $item->unit_price = $itemData['unit_price'];
            $items[] = $item->toArray();
        }

        $preference->items = $items;

        $preference->shipments = (object) [
            'mode' => 'not_specified',
            'cost' => (float) $order->shipping,
        ];

        $preference->back_urls = array(
          "success" => "https://chelato.test/checkout/success/{$order->id}",
          "failure" => "https://chelato.test/checkout/failure/{$order->id}",
          "pending" => "https://chelato.test/checkout/pending/{$order->id}"
        );

        $preference->auto_return = "all";

        // Guardar la preferencia
        $preference->save();
        Log::info('Preference created:', $preference->toArray());

        return $preference;
    }

    public function setCredentials($publicKey, $accessToken)
    {
      // Configurar el acceso a la API de MercadoPago con las credenciales de la tienda
      SDK::setPublicKey($publicKey);
      SDK::setAccessToken($accessToken);
    }


}
