<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_id',
        'phone_number',
        'is_store',
        'phone_number_owner',
        'store_id'
    ];

    /**
     * Obtiene la Store a la que pertenece el numero
     *
     * @return BelongsTo
    */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Obtiene los mensajes enviados
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'from_phone_id', 'phone_id');
    }

    /**
     * Obtiene los mensajes recibidos
     *
     * @return HasMany
    */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_phone_id', 'phone_id');
    }

    /**
     * Obtiene los ultimos mensajes de los chats
     *
     * @return Collection
    */
    public function getLastMessagesForChats(): Collection {
      $sentMessages = $this->messages()->pluck('to_phone_id');
      $receivedMessages = $this->receivedMessages()->pluck('from_phone_id');

      $contactPhoneNumbers = $sentMessages->merge($receivedMessages)->unique()->except($this->phone_id);

      $lastMessages = Message::where(function ($query) use ($contactPhoneNumbers) {
              $query->whereIn('from_phone_id', $contactPhoneNumbers)
                    ->orWhereIn('to_phone_id', $contactPhoneNumbers);
          })
          ->where(function ($query) {
              $query->where('from_phone_id', $this->phone_id)
                    ->orWhere('to_phone_id', $this->phone_id);
          })
          ->latest('message_created')
          ->get()
          ->unique(function ($item) {
              $ids = [$item['from_phone_id'], $item['to_phone_id']];
              sort($ids);
              return implode('-', $ids);
          })
          ->values();

      $messagesWithSender = Message::with('sender')
          ->whereIn('message_id', $lastMessages->pluck('message_id'))
          ->get();

      return $messagesWithSender;
    }
}
