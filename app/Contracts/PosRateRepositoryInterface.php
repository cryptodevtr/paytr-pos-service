<?php

namespace App\Contracts;

interface PosRateRepositoryInterface
{
    public function getAll();
    public function findBestRate(array $criteria);
    public function updateOrCreate(array $data);
    public function truncate();
}
