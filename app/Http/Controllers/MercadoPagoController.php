<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MercadoPagoService;
use App\Repositories\MercadoPagoRepository;

class MercadoPagoController extends Controller
{
    private $mpService;

    public function __construct(MercadoPagoRepository $mercadopagorepo, MercadoPagoService $mpService)
    {
      $this->mercadopagorepo = $mercadopagorepo;
      $this->mpService = $mpService;
    }

    public function webhooks(Request $request)
    {
        Log::info('Datos recibidos de MercadoPago:', [
            'headers' => $request->header(),
            'body' => $request->all(),
        ]);

        $xSignatureParts = explode(',', $request->header('x-signature'));
        $xSignatureData = [];
        foreach ($xSignatureParts as $part) {
            list($key, $value) = explode('=', $part);
            $xSignatureData[trim($key)] = trim($value);
        }

        $ts = $xSignatureData['ts'] ?? '';
        $receivedHash = $xSignatureData['v1'] ?? '';
        $payload = $request->all();
        $dataId = $payload['data']['id'] ?? null;
        $resourceUrl = $payload['resource'] ?? null;
        $id = $dataId ?: basename(parse_url($resourceUrl, PHP_URL_PATH));
        $topic = $payload['topic'] ?? $payload['type'] ?? null;

        if ($id) {
            if ($this->mpService->verifyHMAC($id, $request->header('x-request-id'), $ts, $receivedHash)) {
                Log::info('La verificación HMAC pasó correctamente');

                switch ($topic) {
                  case 'payment':
                    Log::info("Procesando 'payment' con ID: $id");
                    $paymentInfo = $this->mpService->getPaymentInfo($id);

                    if ($paymentInfo) {
                        Log::info("Información del pago recibida:", $paymentInfo);

                        // Aquí deberías agregar la lógica para actualizar el estado del pago a 'paid' en el repositorio
                        $orderId = $paymentInfo['metadata']['order_id']; // Obtiene el ID de la orden desde el metadata
                        $this->mercadopagorepo->updatePaymentStatus($orderId, 'paid'); // Método para actualizar el estado del pago

                        Log::info("Estado del pago actualizado a 'paid' para la orden con ID: $orderId");
                    } else {
                        Log::error("No se pudo obtener información del pago con ID: $id");
                    }

                    return response()->json(['message' => 'Notification received'], 200);


                  case 'merchant_order':
                        // Implementar lógica similar para 'merchant_order'
                        return response()->json(['message' => 'Notification received'], 200);

                  default:
                        Log::warning("Tipo de notificación no soportado: $topic");
                        return response()->json(['error' => 'Unsupported notification type'], 400);
                }
            } else {
                Log::error('La verificación HMAC falló');
                return response()->json(['error' => 'HMAC verification failed'], 400);
            }
        } else {
            Log::error('El índice "data" o "resource" no está presente en los datos de la solicitud');
            return response()->json(['error' => 'Invalid request data'], 400);
        }
    }
}
