<?php

namespace App\Providers;

use App\Contracts\Services\PaymentService as PaymentServiceContract;
use App\Faker\CPFProvider;
use App\Services\PaymentService;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentServiceContract::class, PaymentService::class);

        $this->app->extend(Generator::class.':'.app('config')->get('app.faker_locale'), function (Generator $faker) {
            $faker->addProvider(new CPFProvider($faker));

            return $faker;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
