<?php

namespace App\Http\Requests;

use App\Enums\TransactionPembayaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pelanggan' => 'required|string',
            'no_telpon_pelanggan' => 'required|numeric',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'nullable|integer|min:1',
            'pembayaran' => ['required', Rule::enum(TransactionPembayaran::class)],
        ];
    }
}
