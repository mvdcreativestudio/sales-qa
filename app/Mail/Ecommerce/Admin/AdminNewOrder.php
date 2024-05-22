<?php

namespace App\Mail\Ecommerce\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\EmailTemplate;

class AdminNewOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $template;

    public function __construct(Order $order, EmailTemplate $template)
    {
        $this->order = $order;
        $this->template = $template;
    }

    public function build()
  {
    $template = EmailTemplate::where('name', 'new_order')->firstOrFail();

    // Reemplazar las variables en el cuerpo del correo
    $body = $template->body;
    $variables = [
      '{{ client_name }}' => $order->client->name,
      '{{ client_lastname }}' => $order->client->lastname,
      '{{ client_email }}' => $order->client->email,
      '{{ order_total }}' => $order->total,
      '{{ order_date }}' => $order->date,
      '{{ order_id }}' => $order->id,
      '{{ client_phone }}' => $order->client->phone,
      '{{ client_address }}' => $order->client->address,
      '{{ client_city }}' => $order->client->city,
      '{{ client_state }}' => $order->client->state,
      '{{ client_country }}' => $order->client->country,
  ];

    foreach ($variables as $key => $value) {
        $body = str_replace("{{ $key }}", $value, $body);
    }

    return $this->view('emails.ecommerce.admin.new-order')
                ->with([
                    'subject' => $template->subject,
                    'body' => $body,
                ]);
  }
}
