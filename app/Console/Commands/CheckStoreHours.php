<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Store;
use App\Models\StoreHours;
use Carbon\Carbon;

class CheckStoreHours extends Command
{
    protected $signature = 'stores:check-hours';
    protected $description = 'Check store hours and update their open/closed status';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $day = $this->getDayName($now->dayOfWeek);
        $currentTime = $now->format('H:i');

        $stores = Store::all();

        foreach ($stores as $store) {
            $storeHour = StoreHours::where('store_id', $store->id)
                ->where('day', $day)
                ->first();

            if ($storeHour) {
                if ($storeHour->open_all_day) {
                    // No need to update anything if open all day
                    continue;
                }

                $openTime = Carbon::createFromTimeString($storeHour->open)->format('H:i');
                $closeTime = Carbon::createFromTimeString($storeHour->close)->format('H:i');

                if ($currentTime == $openTime) {
                    $store->closed = 0; // Open
                    $store->save();
                    $this->info("Store ID {$store->id} opened at {$currentTime}");
                }

                if ($currentTime == $closeTime) {
                    $store->closed = 1; // Closed
                    $store->save();
                    $this->info("Store ID {$store->id} closed at {$currentTime}");
                }
            }
        }

        $this->info('Store hours checked and statuses updated.');
    }

    private function getDayName(int $dayOfWeek): string
    {
        $days = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado'
        ];

        return $days[$dayOfWeek];
    }
}
