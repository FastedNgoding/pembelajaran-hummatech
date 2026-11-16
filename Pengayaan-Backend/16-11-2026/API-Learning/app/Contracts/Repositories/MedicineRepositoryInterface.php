<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface MedicineRepositoryInterface extends BaseRepositoryInterface
{
    public function search(?string $keyword, ?string $category, int $perPage = 15): LengthAwarePaginator;

    public function findBySku(string $sku): ?Model;
}