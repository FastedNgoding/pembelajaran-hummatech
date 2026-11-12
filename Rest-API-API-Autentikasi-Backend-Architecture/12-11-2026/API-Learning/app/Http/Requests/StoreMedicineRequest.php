<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ignoreId = $this->route('id');

        return [
            'sku' => ['required', 'string', 'max:50', Rule::unique('medicines', 'sku')->ignore($ignoreId)],
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'requires_prescription' => ['boolean'],
            'expired_at' => ['required', 'date', 'after:today'],
        ];
    }
}