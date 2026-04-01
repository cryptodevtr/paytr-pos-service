<?php

namespace App\Services;

use App\DTOs\POSRateDTO;
use App\Exceptions\ApiFetchException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.mock_api.url', 'https://6899a45bfed141b96ba02e4f.mockapi.io/paytr/ratios');
    }

    /**
     * Fetch rates and return as DTO collection
     *
     * @return POSRateDTO[]
     * @throws ApiFetchException
     */
    public function fetchRates(): array
    {
        try {
            $response = Http::timeout(30)
                ->retry(3, 100)
                ->acceptJson()
                ->get($this->apiUrl);

            if ($response->failed()) {
                throw new ApiFetchException(
                    'API responded with status: ' . $response->status()
                );
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new ApiFetchException('Invalid response format from API');
            }

            // DTO
            return array_map(
                fn($item) => POSRateDTO::fromArray($item),
                $data
            );

        } catch (\Exception $e) {
            Log::error('API Fetch Error: ' . $e->getMessage(), [
                'url' => $this->apiUrl,
                'trace' => $e->getTraceAsString()
            ]);

            throw new ApiFetchException(
                'Failed to fetch rates from external API: ' . $e->getMessage()
            );
        }
    }
}
