<?php

namespace App\Contracts;

use App\DTOs\POSRateDTO;
use App\Models\PosRate;

interface PosRateRepositoryInterface
{
    public function getAllAsDTO(): array;
    public function findBestRateAsDTO(array $criteria): ?POSRateDTO;
    public function updateOrCreateFromDTO(POSRateDTO $dto): PosRate;
    public function bulkCreateFromDTOs(array $dtos): void;
    public function deleteAll(): void;
}
