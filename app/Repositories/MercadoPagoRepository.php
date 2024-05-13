<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MercadoPagoService;
use App\Repositories\PedidosYaRepository;

class MercadoPagoRepository
{
  /**
   * El repositorio de PedidosYa para la gestión de envíos.
   *
   * @var PedidosYaRepository
  */
  protected $pedidosYaRepository;

  /**
   * Inyecta el repositorio de PedidosYa en el repositorio.
   *
   * @param PedidosYaRepository $pedidosYaRepository
  */
  public function __construct(PedidosYaRepository $pedidosYaRepository)
  {
    $this->pedidosYaRepository = $pedidosYaRepository;
  }

  /**
   * Maneja las notificaciones webhook de MercadoPago.
   *
   * @param Request $request
   * @param MercadoPagoService $mpService
   * @return array
  */
  public function handleWebhook(Request $request, MercadoPagoService $mpService): array
  {
    $xSignatureParts = explode(',', $request->header('x-signature'));
    $xSignatureData = $this->parseXSignature($xSignatureParts);

    $ts = $xSignatureData['ts'] ?? '';
    $receivedHash = $xSignatureData['v1'] ?? '';
    $payload = $request->all();
    $dataId = $payload['data']['id'] ?? null;
    $resourceUrl = $payload['resource'] ?? null;
    $id = $dataId ?: basename(parse_url($resourceUrl, PHP_URL_PATH));
    $topic = $payload['topic'] ?? $payload['type'] ?? null;

    if ($id) {
        if ($mpService->verifyHMAC($id, $request->header('x-request-id'), $ts, $receivedHash)) {
            Log::info('La verificación HMAC pasó correctamente');
            return $this->processNotification($topic, $id, $mpService);
        } else {
            Log::error('La verificación HMAC falló');
            return ['message' => ['error' => 'HMAC verification failed'], 'status' => 400];
        }
    } else {
        Log::error('El índice "data" o "resource" no está presente en los datos de la solicitud');
        return ['message' => ['error' => 'Invalid request data'], 'status' => 400];
    }
  }

  /**
   * Procesa las notificaciones según el tipo de evento.
   *
   * @param string $topic
   * @param string $id
   * @param MercadoPagoService $mpService
   * @return array
  */
  private function processNotification(string $topic, string $id, MercadoPagoService $mpService): array
  {
    switch ($topic) {
        case 'payment':
            Log::info("Procesando 'payment' con ID: $id");
            $paymentInfo = $mpService->getPaymentInfo($id);

            if ($paymentInfo) {
                Log::info("Información del pago recibida:", $paymentInfo);

                $orderId = $paymentInfo['metadata']['order_id']; // Obtiene el ID de la orden desde el metadata
                $this->updatePaymentStatus($orderId, 'paid'); // Método para actualizar el estado del pago

                Log::info("Estado del pago actualizado a 'paid' para la orden con ID: $orderId");
                return ['message' => ['message' => 'Notification received'], 'status' => 200];
            } else {
                Log::error("No se pudo obtener información del pago con ID: $id");
                return ['message' => ['error' => 'Payment information not found'], 'status' => 400];
            }

        case 'merchant_order':
            // Implementar lógica similar para 'merchant_order'
            return ['message' => ['message' => 'Notification received'], 'status' => 200];

        default:
            Log::warning("Tipo de notificación no soportado: $topic");
            return ['message' => ['error' => 'Unsupported notification type'], 'status' => 400];
    }
  }

  /**
   * Actualiza el estado del pago de una orden.
   *
   * @param int $orderId
   * @param string $status
   * @return bool
  */
  public function updatePaymentStatus(int $orderId, string $status): bool
  {
    $order = Order::find($orderId);

    if ($order) {
        $order->payment_status = $status;
        $order->save();

        Log::info("Estado de la orden actualizado a '$status' para la orden con ID: $orderId");

        if ($status === 'paid' && $order->shipping_method === 'peya') {
            $this->createPeYaShipping($order);
        }

        return true;
    } else {
        Log::error("No se encontró la orden con ID: $orderId");
        return false;
    }
  }

  /**
   * Crea el envío en PedidosYa si el método de envío es 'peya'.
   *
   * @param Order $order
   * @return void
  */
  private function createPeYaShipping(Order $order): void
  {
    $request = new Request([
        'estimate_id' => $order->estimate_id,
    ]);

    $response = $this->pedidosYaRepository->confirmOrderRequest($request);

    if (isset($response['shippingId'])) {
        $order->shipping_id = $response['shippingId'];
        $order->shipping_status = $response['status'];
        $order->save();

        Log::info("Envío creado con éxito en PedidosYa para la orden con ID: $order->id");
    } else {
        Log::error("Error al crear el envío en PedidosYa para la orden con ID: $order->id", ['response' => $response]);
    }
  }

  /**
   * Analiza los datos de la firma X-Signature.
   *
   * @param array $xSignatureParts
   * @return array
  */
  private function parseXSignature(array $xSignatureParts): array
  {
      $xSignatureData = [];
      foreach ($xSignatureParts as $part) {
          list($key, $value) = explode('=', $part);
          $xSignatureData[trim($key)] = trim($value);
      }
      return $xSignatureData;
  }
}
