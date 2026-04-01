<?php

use App\Jobs\SyncPosRatesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// POS oranlarını her gün saat 03:00'te otomatik güncelle
Schedule::job(new SyncPosRatesJob)->dailyAt('03:00');
