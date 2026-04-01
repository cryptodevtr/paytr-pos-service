<?php

namespace App\Providers;

use App\Contracts\PosRateRepositoryInterface;
use App\Contracts\PosSelectionServiceInterface;
use App\Repositories\PosRateRepository;
use App\Services\PosSelectionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PosRateRepositoryInterface::class, PosRateRepository::class);
        $this->app->bind(PosSelectionServiceInterface::class, PosSelectionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
