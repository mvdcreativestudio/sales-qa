<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;



class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $fromPhoneNumberOwner;
    public $isSender = false;

    /**
     * Crea una nueva instancia del evento.
     *
     * @param  Message $message
     * @param  string $fromPhoneNumberOwner
     *
     * @return void
    */
    public function __construct(Message $message, string $fromPhoneNumberOwner, bool $isSender = false)
    {
        $this->message = $message;
        $this->fromPhoneNumberOwner = $fromPhoneNumberOwner;
        $this->isSender = $isSender;
    }

    /**
     * Obtiene los canales a través de los cuales se transmitirá el evento.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->isSender) {
          return new PrivateChannel('messages.' . $this->message->from_phone_id);
        } else {
          return new PrivateChannel('messages.' . $this->message->to_phone_id);
        }
    }

    /**
     * Da nombre al evento para la consola.
      * @return string
    */
    public function broadcastAs()
    {
        return 'message.received';
    }
}
