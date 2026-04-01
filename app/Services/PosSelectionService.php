<?php

namespace App\Services;

use App\Contracts\PosRateRepositoryInterface;
use App\Contracts\PosSelectionServiceInterface;
use App\Exceptions\NoPosFoundException;

class PosSelectionService implements PosSelectionServiceInterface
{
    private PosRateRepositoryInterface $repository;

    public function __construct(PosRateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $paymentDetails
     * @return array
     * @throws NoPosFoundException
     */
    public function selectBestPos(array $paymentDetails): array
    {
        $requiredFields = ['installment', 'currency', 'card_type'];
        foreach ($requiredFields as $field) {
            if (empty($paymentDetails[$field])) {
                throw new \InvalidArgumentException("{$field} is required");
            }
        }

        $bestRateDTO = $this->repository->findBestRateAsDTO($paymentDetails);

        if (!$bestRateDTO) {
            throw new NoPosFoundException(
                criteria: $paymentDetails,
                message: sprintf(
                    'No POS found for criteria: installment=%d, currency=%s, card_type=%s, card_brand=%s',
                    $paymentDetails['installment'],
                    $paymentDetails['currency'],
                    $paymentDetails['card_type'],
                    $paymentDetails['card_brand'] ?? 'any'
                )
            );
        }

        $amount = $paymentDetails['amount'] ?? 0;
        $cost = $this->calculateCost($amount, $bestRateDTO->commission_rate);

        return [
            'pos_name' => $bestRateDTO->pos_name,
            'card_type' => $bestRateDTO->card_type,
            'card_brand' => $bestRateDTO->card_brand,
            'installment' => $bestRateDTO->installment,
            'currency' => $bestRateDTO->currency,
            'commission_rate' => $bestRateDTO->commission_rate,
            'cost' => $cost,
            'selected_rate' => $bestRateDTO->commission_rate * 100 . '%'
        ];
    }

    /**
     * @param float $amount
     * @param float $commissionRate
     * @return float
     */
    public function calculateCost(float $amount, float $commissionRate): float
    {
        return round($amount * $commissionRate, 2);
    }
}
