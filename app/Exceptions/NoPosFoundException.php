<?php

namespace App\Exceptions;

use Exception;

class NoPosFoundException extends Exception
{
    protected array $criteria;

    public function __construct(array $criteria = [], string $message = "No suitable POS found for given criteria")
    {
        parent::__construct($message);
        $this->criteria = $criteria;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
