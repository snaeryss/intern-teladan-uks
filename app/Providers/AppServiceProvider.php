<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\MedicalRecordServiceInterface;
use App\Services\MedicalRecordService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MedicalRecordServiceInterface::class, MedicalRecordService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
