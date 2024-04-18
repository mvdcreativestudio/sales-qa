<?php

namespace App\Http\Controllers;

use App\Repositories\WhatsAppRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\FetchMessagesRequest;

class WhatsAppController extends Controller
{
    /**
     * El repositorio para las operaciones de WhatsApp.
    */
    protected WhatsAppRepository $whatsAppRepo;

    /**
     * Inicializa el controlador
     *
     * @param WhatsAppRepository $whatsAppRepo
    */
    public function __construct(WhatsAppRepository $whatsAppRepo)
    {
        $this->whatsAppRepo = $whatsAppRepo;
    }

    /**
     * Webhook encargado de recibir los mensajes enviados y recibidos por WhatsApp.
     *
     * @return void
    */
    public function webhook()
    {
        $this->whatsAppRepo->webhook();
    }

    /**
     * Procesa los mensajes recibidos de WhatsApp.
     *
     * @return void
    */
    public function recibe()
    {
        $this->whatsAppRepo->recibe();
    }

    /**
     * Maneja la solicitud para buscar mensajes entre el número de teléfono de la tienda del usuario y otro número.
     *
     * @param \Illuminate\Http\FetchMessagesRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function fetchMessages(FetchMessagesRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $contactPhoneNumber = $request->input('phone_number');

        $result = $this->whatsAppRepo->fetchMessagesForUserStore($userId, $contactPhoneNumber);

        if (array_key_exists('error', $result)) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(['messages' => $result['messages']]);
    }

    /**
     * Envía un mensaje de WhatsApp a un número de teléfono.
     *
     * @param \Illuminate\Http\SendMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function send(SendMessageRequest $request): JsonResponse
    {
        $phoneNumber = $request->input('phone_number');
        $messageContent = $request->input('message');
        $fromPhoneNumberId = $request->input('from_phone_number_id');

        $result = $this->whatsAppRepo->sendMessage($phoneNumber, $messageContent, $fromPhoneNumberId);

        return response()->json($result);
    }
}
