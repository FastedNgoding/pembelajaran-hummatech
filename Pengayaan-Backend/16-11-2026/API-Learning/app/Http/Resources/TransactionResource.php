<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'kasir_id' => $this->kasir_id,
            'medicine_id' => $this->medicine_id,
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_telpon_pelanggan' => $this->no_telpon_pelanggan,
            'pembayaran' => $this->pembayaran,
        ];
    }
}
