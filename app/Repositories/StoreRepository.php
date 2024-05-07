<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
    /**
     * Devuelve todas las tiendas.
     *
     * @return Collection|Store[]
     */
    public function getAll(): Collection
    {
      return Store::withCount('users')->with('phoneNumber')->get();
    }

    /**
     * Crea una nueva tienda con los datos proporcionados.
     *
     * @param array $data
     * @return Store
     */
    public function create(array $data): Store
    {
        return Store::create($data);
    }

    /**
     * Actualiza una tienda existente con los datos proporcionados.
     *
     * @param Store $store
     * @param array $data
     * @return Store
     */
    public function update(Store $store, array $data): Store
    {
        $store->update($data);
        return $store;
    }

    /**
     * Elimina una tienda de la base de datos.
     *
     * @param Store $store
     * @return bool|null
     */
    public function delete(Store $store): ?bool
    {
        return $store->delete();
    }

    /**
     * Devuelve usuarios que no están asociados a ninguna tienda.
     *
     * @return Collection|User[]
     */
    public function getUnassociatedUsers(): Collection
    {
        return User::whereNull('store_id')->get();
    }


    /**
     * Asocia un usuario a una tienda.
     *
     * @param Store $store
     * @param int $userId
     * @return Store
     */
    public function associateUser(Store $store, int $userId): Store
    {
        $user = User::findOrFail($userId);
        $user->store_id = $store->id;
        $user->save();

        return $store;
    }

    /**
     * Desasocia un usuario de una tienda.
     *
     * @param Store $store
     * @param int $userId
     * @return Store
     */
    public function disassociateUser(Store $store, int $userId): Store
    {
        $user = User::findOrFail($userId);
        $user->store_id = null;
        $user->save();

        return $store;
    }

    public function saveStoreHours(Store $store, array $hoursData): Store
    {
        foreach ($hoursData as $day => $data) {
            // Asegúrate de verificar si necesitas realmente añadir segundos
            $open = $this->formatTime($data['open']);
            $close = $this->formatTime($data['close']);

            $store->storeHours()->updateOrCreate(
                ['day' => $day, 'store_id' => $store->id],
                [
                    'open' => $open,
                    'close' => $close,
                    'open_all_day' => $data['open_all_day'] ?? false  // Cambiado para reflejar la columna correcta
                ]
            );
        }

        return $store;
    }

    /**
     * Formatea el tiempo para asegurar que el formato sea HH:MM:SS.
     * Si no se proporciona segundos, añade ':00'.
     * Si el tiempo está vacío, devuelve null.
     */
    protected function formatTime($time) {
        if (empty($time)) {
            return null;
        }

        $parts = explode(':', $time);
        if (count($parts) < 3) {
            return $time . ':00';
        }
        return $time;
    }


    public function getStoresWithStatus()
    {
        $stores = Store::with('storeHours')->get();
        $now = Carbon::now()->format('H:i:s');
        $currentDay = ucfirst(Carbon::now()->isoFormat('dddd')); // Obtener el día actual en español con la primera letra en mayúscula

        // Declaración de registro para el día actual
        \Log::info('Día actual:', ['currentDay' => $currentDay]);

        foreach ($stores as $store) {
            // Comprobación inicial para ver si la apertura/cierre es manual
            if ($store->closed) {
                $store->status = 'Cerrada';
                continue;  // No necesita verificar más si hay una entrada manual que indica cerrado
            }

            $store->status = 'Cerrada';  // Presumir cerrada por defecto
            $storeOpenForToday = false;

            if (!$store->closed) {  // Solo si la tienda no está cerrada globalmente por la columna 'closed'
                foreach ($store->storeHours as $hours) {
                    // Declaración de registro para el día y horario de la tienda
                    \Log::info('Día y horario de la tienda:', ['store_id' => $hours->store_id, 'day' => $hours->day, 'open' => $hours->open, 'close' => $hours->close, 'open_all_day' => $hours->open_all_day]);

                    // Si es el día actual y la tienda está abierta todo el día
                    if ($hours->day === $currentDay && $hours->open_all_day) {
                        $store->status = 'Abierta';
                        $storeOpenForToday = true;
                        break;  // Termina el bucle si encuentra que está abierto todo el día hoy
                    }

                    // Si no está abierta todo el día, verificar si el día actual coincide
                    if ($hours->day === $currentDay) {
                        if ($now >= $hours->open && $now <= $hours->close) {
                            $store->status = 'Abierta';
                            $storeOpenForToday = true;
                            break;
                        }
                    }
                }

                // Si llegamos al día actual y no se ha establecido como abierto
                if (!$storeOpenForToday && $hours->day === $currentDay) {
                    $store->status = 'Cerrada';
                }
            }
        }

        return $stores;
    }












}
