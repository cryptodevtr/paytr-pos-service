<?php

namespace App\Repositories;

use App\Contracts\PosRateRepositoryInterface;
use App\Models\PosRate;

class PosRateRepository implements PosRateRepositoryInterface
{
    public function getAll()
    {
        return PosRate::all();
    }

    public function findBestRate(array $criteria)
    {
        $query = PosRate::query();

        if (isset($criteria['card_type'])) {
            $query->where('card_type', $criteria['card_type']);
        }

        if (isset($criteria['card_brand'])) {
            $query->where('card_brand', $criteria['card_brand']);
        } else {
            // Eğer kart markası belirtilmemişse, tüm markaları dikkate al
            $query->whereNotNull('card_brand');
        }

        if (isset($criteria['installment'])) {
            $query->where('installment', $criteria['installment']);
        }

        if (isset($criteria['currency'])) {
            $query->where('currency', $criteria['currency']);
        }

        return $query->orderBy('commission_rate', 'asc')->first();
    }

    public function updateOrCreate(array $data)
    {
        //
        return PosRate::updateOrCreate(
            [ // şartlar
                'pos_name' => $data['pos_name'],
                'card_type' => $data['card_type'],
                'card_brand' => $data['card_brand'],
                'installment' => $data['installment'],
                'currency' => $data['currency']
            ],
            ['commission_rate' => $data['commission_rate']] // güncellenecek veya eklenecek veriler
        );
    }

    public function truncate()
    {
        PosRate::truncate();
    }
}
