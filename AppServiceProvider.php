<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No custom application services to register at this time
    }

    /**
     * Bootstrap any application services.
     *
     * Sets default string length, enables strict mode in non-production,
     * and adds custom response macros for API consistency.
     */
    public function boot(): void
    {
        // Set default string length for database migrations
        Schema::defaultStringLength(191);

        // Enable strict mode in non-production
        if (config('app.env') !== 'production') {
            Model::preventLazyLoading();
            Model::preventSilentlyDiscardingAttributes();
        }

        // Add custom response macros
        Response::macro('success', function ($data = null, $message = 'Success', $status = 200) {
            return new JsonResponse([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function ($message = 'Error', $errors = [], $status = 400) {
            return new JsonResponse([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $status);
        });

        // Add validation response macro
        Response::macro('validation', function ($errors) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        });
    }
}
