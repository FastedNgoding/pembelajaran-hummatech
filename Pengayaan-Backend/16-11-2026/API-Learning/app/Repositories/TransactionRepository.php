<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }
}
