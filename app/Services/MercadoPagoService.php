<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;


class MercadoPagoService
{

    public function __construct()
    {
        // Cargar la secret key desde la configuración
        $this->secretKey = config('services.mercadopago.secret_key');
        $this->client = new Client();

        // Configurar el acceso a la API de MercadoPago
        SDK::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function createPreference($preferenceData, $order)
    {
        // Crea la preferencia de MercadoPago
        $preference = new Preference();

        // Configurar el pagador
        $payer = new \stdClass();
        $payer->email = $preferenceData['payer']['email'];
        $preference->payer = $payer;
        $preference->currency_id = 'UYU';

        // Asegúrate de usar un arreglo para el campo metadata
        $preference->metadata = [
            'order_id' => $order->id
        ];

        // Configurar los ítems
        $items = [];
        foreach ($preferenceData['items'] as $itemData) {
            $item = new Item();
            $item->title = $itemData['title'];
            $item->description = 'descripcion';
            $item->quantity = $itemData['quantity'];
            $item->unit_price = $itemData['unit_price'];
            $items[] = $item;
        }

        $preference->items = $items;

        // Configurar las URLs de retorno
        $preference->back_urls = [
            "success" => "https://google.com",
            "failure" => "https://mvdcreativestudio.com",
            "pending" => "https://sumeria.com.uy"
        ];
        $preference->auto_return = "all";

        // URL para las notificaciones webhooks
        $preference->notification_url = 'https://cce5-2800-a4-1650-ad00-b5d7-6e7-6d71-b1ac.ngrok-free.app/api/mpagohook?source_news=webhooks';

        // Guarda la preferencia y genera el log
        $preference->save();
        Log::info('Preference created:', $preference->toArray());

        return $preference;
    }

    public function verifyHMAC($id, $requestId, $timestamp, $receivedHash)
    {
        $message = "id:$id;request-id:$requestId;ts:$timestamp;";
        $generatedHash = hash_hmac('sha256', $message, $this->secretKey);

        return hash_equals($generatedHash, $receivedHash);
    }

    public function getPaymentInfo($id)
    {
        try {
            $response = $this->client->request('GET', "https://api.mercadopago.com/v1/payments/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.mercadopago.access_token'),
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Error al obtener la información del pago: " . $e->getMessage());
            return null;
        }
    }


    public function setCredentials($publicKey, $accessToken)
    {
      // Configurar el acceso a la API de MercadoPago con las credenciales de la tienda
      SDK::setPublicKey($publicKey);
      SDK::setAccessToken($accessToken);
    }


}
