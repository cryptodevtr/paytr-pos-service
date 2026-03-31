<?php

namespace App\Contracts;

interface PosSelectionServiceInterface
{
    public function selectBestPos(array $paymentDetails): array;
    public function calculateCost(float $amount, float $commissionRate): float;
}
