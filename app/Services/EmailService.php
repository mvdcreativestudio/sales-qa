<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EcommerceSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Envía un correo electrónico basado en una plantilla.
     *
     * @param string $templateName
     * @param array $variables
     * @param string $recipient
     */
    private function sendTemplateEmail(string $templateName, array $variables, string $recipient)
    {
        try {
            $template = EmailTemplate::where('name', $templateName)->firstOrFail();

            $subject = $this->replaceVariables($template->subject, $variables);
            $body = $this->replaceVariables($template->body, $variables);

            Log::info('Cuerpo del correo después de reemplazar las variables:', ['body' => $body]);

            Mail::send([], [], function ($message) use ($recipient, $subject, $body) {
                $message->to($recipient)
                    ->subject($subject)
                    ->html($body); // Usar método html aquí
            });

            Log::info('Correo enviado a ' . $recipient . ' con el asunto ' . $subject);
        } catch (\Exception $e) {
            Log::error('Error enviando correo: ' . $e->getMessage());
        }
    }

    /**
     * Reemplaza las variables en la plantilla.
     *
     * @param string $content
     * @param array $variables
     * @return string
     */
    private function replaceVariables(string $content, array $variables): string
    {
        Log::info('Contenido original:', ['content' => $content]);

        foreach ($variables as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }

        Log::info('Contenido después del reemplazo:', ['content' => $content]);

        return $content;
    }

    /**
     * Envía un correo de nueva orden al administrador de la tienda.
     *
     * @param array $variables
     */
    public function sendNewOrderEmail(array $variables)
    {
        try {
            $ecommerceSettings = EcommerceSetting::firstOrFail();
            $recipient = $ecommerceSettings->notifications_email;
            $this->sendTemplateEmail('new-order', $variables, $recipient);
        } catch (\Exception $e) {
            Log::error('Error obteniendo configuraciones de ecommerce: ' . $e->getMessage());
        }
    }

    /**
     * Envía un correo de nueva orden al cliente.
     *
     * @param array $variables
     */
    public function sendNewOrderClientEmail(array $variables)
    {
        if (isset($variables['client_email'])) {
            $recipient = $variables['client_email'];
            $this->sendTemplateEmail('new-order-client', $variables, $recipient);
        } else {
            Log::error('El email del cliente no está definido en las variables.');
        }
    }

    /**
     * Envía un correo de actualización de producto al administrador de la tienda.
     *
     * @param array $variables
     */
    public function sendProductUpdateEmail(array $variables)
    {
        try {
            $ecommerceSettings = EcommerceSetting::firstOrFail();
            $recipient = $ecommerceSettings->notifications_email;
            $this->sendTemplateEmail('product-update', $variables, $recipient);
        } catch (\Exception $e) {
            Log::error('Error obteniendo configuraciones de ecommerce: ' . $e->getMessage());
        }
    }
}
