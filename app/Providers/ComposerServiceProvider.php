<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Usando Closure
        View::composer('*', function ($view) {
            $settings = \DB::table('ecommerce_settings')->first();
            $view->with('settings', $settings);
        });
    }

}
