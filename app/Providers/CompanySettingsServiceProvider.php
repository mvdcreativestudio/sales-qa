<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CompanySettings;

class CompanySettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('companySettings', function ($app) {
            return CompanySettings::first(); 
        });
    }

    public function boot()
    {
        view()->share('companySettings', $this->app->make('companySettings'));
    }
}
