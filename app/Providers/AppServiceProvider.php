<?php

namespace App\Providers;

use App\Services\Quotes\QuotesManager;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            QuotesManager::class,
            static fn (Container $container) => new QuotesManager($container)
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
