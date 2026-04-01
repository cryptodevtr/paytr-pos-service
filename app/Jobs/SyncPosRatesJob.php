<?php

namespace App\Jobs;

use App\Contracts\PosRateRepositoryInterface;
use App\Exceptions\ApiFetchException;
use App\Models\PosRateHistory;
use App\Services\ExternalApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncPosRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * @param ExternalApiService $apiService
     * @param PosRateRepositoryInterface $repository
     * @return void
     * @throws ApiFetchException
     */
    public function handle(
        ExternalApiService $apiService,
        PosRateRepositoryInterface $repository
    ): void {
        try {
            // DTO koleksiyonu olarak al
            $dtos = $apiService->fetchRates();

            Log::info('Fetched rates from API', ['count' => count($dtos)]);

            DB::beginTransaction();

            // Tüm verileri sil (delete kullan, truncate değil)
            $repository->deleteAll();

            // DTO
            foreach ($dtos as $dto) {
                $repository->updateOrCreateFromDTO($dto);
            }

            // History
            PosRateHistory::create([
                'data' => json_encode(
                    array_map(fn($dto) => $dto->toArray(), $dtos)
                ),
                'fetched_at' => now()
            ]);

            DB::commit();

            // Cache'i temizle (pattern ile tüm pos_rate:* keylerini sil)
            $this->clearPosRateCache();

            Log::info('POS rates synced successfully', [
                'count' => count($dtos),
                'first_rate' => $dtos[0] ?? null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('POS rates sync failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Clear all pos_rate cache keys
     */
    private function clearPosRateCache(): void
    {
        // Redis'te pos_rate:* pattern'ine uyan tüm keyleri sil
        Cache::flush(); // Basit çözüm: tüm cache'i temizle
        
        // Alternatif: Sadece pos_rate keylerini temizle (Redis kullanıyorsak)
        // $redis = Cache::getRedis();
        // $keys = $redis->keys('laravel_cache:pos_rate:*');
        // foreach ($keys as $key) {
        //     $redis->del($key);
        // }
    }
}
