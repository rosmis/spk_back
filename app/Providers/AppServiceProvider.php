<?php

namespace App\Providers;

use App\Contracts\ShopifyInterface;
use App\Dto\ShopifyConfigDto;
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
            ShopifyConfigDto::class,
            static fn () => ShopifyConfigDto::fromArray(config('shopify')),
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
