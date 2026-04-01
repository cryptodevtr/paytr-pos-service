<?php

namespace App\Repositories;

use App\Contracts\PosRateRepositoryInterface;
use App\DTOs\POSRateDTO;
use App\Models\PosRate;
use Illuminate\Support\Facades\DB;

class PosRateRepository implements PosRateRepositoryInterface
{
    /**
     * @return POSRateDTO[]
     */
    public function getAllAsDTO(): array
    {
        return PosRate::all()->map(function ($posRate) {
            return new POSRateDTO(
                pos_name: $posRate->pos_name,
                card_type: $posRate->card_type,
                card_brand: $posRate->card_brand,
                installment: $posRate->installment,
                currency: $posRate->currency,
                commission_rate: (float) $posRate->commission_rate
            );
        })->toArray();
    }

    public function findBestRateAsDTO(array $criteria): ?POSRateDTO
    {
        $query = PosRate::query();

        if (isset($criteria['card_type'])) {
            $query->where('card_type', $criteria['card_type']);
        }

        if (isset($criteria['card_brand'])) {
            $query->where('card_brand', $criteria['card_brand']);
        } else {
            $query->whereNotNull('card_brand');
        }

        if (isset($criteria['installment'])) {
            $query->where('installment', $criteria['installment']);
        }

        if (isset($criteria['currency'])) {
            $query->where('currency', $criteria['currency']);
        }

        $posRate = $query->orderBy('commission_rate', 'asc')->first();

        if (!$posRate) {
            return null;
        }

        return new POSRateDTO(
            pos_name: $posRate->pos_name,
            card_type: $posRate->card_type,
            card_brand: $posRate->card_brand,
            installment: $posRate->installment,
            currency: $posRate->currency,
            commission_rate: (float) $posRate->commission_rate
        );
    }

    public function updateOrCreateFromDTO(POSRateDTO $dto): PosRate
    {
        return PosRate::updateOrCreate(
            [
                'pos_name' => $dto->pos_name,
                'card_type' => $dto->card_type,
                'card_brand' => $dto->card_brand,
                'installment' => $dto->installment,
                'currency' => $dto->currency
            ],
            ['commission_rate' => $dto->commission_rate]
        );
    }

    public function deleteAll(): void
    {
        PosRate::query()->delete();
    }

    public function bulkCreateFromDTOs(array $dtos): void
    {
        DB::transaction(function () use ($dtos) {
            foreach ($dtos as $dto) {
                $this->updateOrCreateFromDTO($dto);
            }
        });
    }
}
