<?php

use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::prefix('pos')->group(function () {
    Route::post('/select', [PosController::class, 'selectBestPos']);
    Route::post('/sync', [PosController::class, 'syncRates']);
    Route::post('/sync/trigger', [PosController::class, 'triggerSync']);
});
