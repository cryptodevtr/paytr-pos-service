<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosRate extends Model
{
    protected $fillable = [
        'pos_name', 'card_type', 'card_brand',
        'installment', 'currency', 'commission_rate'
    ];
}
