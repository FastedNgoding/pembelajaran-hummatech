<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;

interface TransactionServiceInterface
{
    public function create(array $data): Model;
}
