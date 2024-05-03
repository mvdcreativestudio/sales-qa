<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function createPreference(Request $request)
    {
        $client = new Client();
        $response = $client->request('POST', 'https://api.mercadopago.com/checkout/preferences', [
            'headers' => [
                'Authorization' => 'Bearer YOUR_ACCESS_TOKEN',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'items' => [
                    [
                        'title' => $request->title,
                        'description' => $request->description,
                        'quantity' => 1,
                        'currency_id' => 'ARS',
                        'unit_price' => $request->price,
                    ],
                ],
            ],
        ]);

        $preference = json_decode((string) $response->getBody(), true);

        return view('your_payment_view', [
            'preferenceId' => $preference['id'],
        ]);
    }
}
