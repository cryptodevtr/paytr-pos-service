<?php

namespace App\DTOs;

class POSRateDTO
{
    public function __construct(
        public readonly string $pos_name,
        public readonly string $card_type,
        public readonly string $card_brand,
        public readonly int $installment,
        public readonly string $currency,
        public readonly float $commission_rate
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            pos_name: $data['pos_name'],
            card_type: $data['card_type'],
            card_brand: $data['card_brand'],
            installment: (int) $data['installment'],
            currency: $data['currency'],
            commission_rate: (float) $data['commission_rate']
        );
    }

    public function toArray(): array
    {
        return [
            'pos_name' => $this->pos_name,
            'card_type' => $this->card_type,
            'card_brand' => $this->card_brand,
            'installment' => $this->installment,
            'currency' => $this->currency,
            'commission_rate' => $this->commission_rate
        ];
    }
}
