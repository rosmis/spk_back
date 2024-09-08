<?php

namespace App\Providers;

use App\Contracts\ShopifyInterface;
use App\Factories\ShopifyConfigDtoFactory;
use App\Services\ShopifyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ShopifyConfigDtoFactory::class, ShopifyConfigDtoFactory::class
        );

        $this->app->bind(ShopifyInterface::class, ShopifyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
