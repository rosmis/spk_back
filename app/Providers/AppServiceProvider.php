<?php

namespace App\Providers;

use App\Contracts\ShopifyInterface;
use App\Factories\ShopifyConfigDtoFactory;
use App\Models\User;
use App\Services\ShopifyService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        Gate::define('viewPulse', function (User $user) {
            return $user->email === 'rosmis123@gmail.com';
        });

        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });
    }
}
