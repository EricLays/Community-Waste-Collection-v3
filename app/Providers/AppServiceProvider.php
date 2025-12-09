<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\HouseholdRepositoryInterface::class,
            \App\Repositories\Mongo\HouseholdMongoRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\PaymentRepositoryInterface::class,
            \App\Repositories\Mongo\PaymentMongoRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\WasteRepositoryInterface::class,
            \App\Repositories\Mongo\WasteMongoRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ReportRepository::class,
            \App\Repositories\Mongo\ReportMongoRepository::class
        );
    }
}
