<?php

namespace App\Providers;

use App\Services\KnetConfigLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        app()->setLocale('ar');
        Paginator::useBootstrapFive();

        if (! $this->app->runningInConsole()) {
            KnetConfigLoader::applyFromDatabase();
        }
    }
}
