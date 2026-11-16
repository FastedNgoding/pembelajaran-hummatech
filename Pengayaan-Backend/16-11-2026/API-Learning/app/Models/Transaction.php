<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'kasir_id',
        'medicine_id',
        'nama_pelanggan',
        'no_telpon_pelanggan',
        'pembayaran'
    ];
}
