<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CompanySettings;
use Illuminate\Support\Facades\Schema;

class CompanySettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('companySettings', function ($app) {
            if (Schema::hasTable('company_settings')) {
                return CompanySettings::first();
            } else {
                return null;
            }
        });
    }

    public function boot()
    {
        if (Schema::hasTable('company_settings')) {
            view()->share('companySettings', $this->app->make('companySettings'));
        }
    }
}
