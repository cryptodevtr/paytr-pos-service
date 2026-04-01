<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosRateHistory extends Model
{
    protected $fillable = [
        'data',
        'fetched_at'
    ];

    protected $casts = [
        'data' => 'array',
        'fetched_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->fetched_at) {
                $model->fetched_at = now();
            }
        });
    }
}
