<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'category',
        'price',
        'stock',
        'requires_prescription',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'requires_prescription' => 'boolean',
            'expired_at' => 'date',
        ];
    }
}
