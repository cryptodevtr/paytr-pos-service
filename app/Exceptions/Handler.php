<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Loglama yapılacak
        });

        // ApiFetchException için raporlama
        $this->reportable(function (ApiFetchException $e) {
            // Özel loglama yapabiliriz
            \Illuminate\Support\Facades\Log::channel('api')->error($e->getMessage(), [
                'context' => $e->getContext(),
                'trace' => $e->getTraceAsString()
            ]);
        });

        // API için exception render
        $this->renderable(function (ApiFetchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'type' => 'api_fetch_error'
                ], 503); // Service Unavailable
            }
        });

        $this->renderable(function (NoPosFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'type' => 'no_pos_found'
                ], 404);
            }
        });
    }

    /**
     * Özel log kanalı için
     */
    protected function context(): array
    {
        return array_merge(parent::context(), [
            'environment' => app()->environment(),
        ]);
    }
}
