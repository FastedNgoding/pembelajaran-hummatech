<?php

namespace App\Repositories;

use App\Contracts\Repositories\MedicineRepositoryInterface;
use App\Models\Medicine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class MedicineRepository extends BaseRepository implements MedicineRepositoryInterface
{
    public function __construct(Medicine $model)
    {
        parent::__construct($model);
    }

    public function search(?string $keyword, ?string $category, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when($keyword, fn ($query) => $query->where('name', 'like', "%{$keyword}%"))
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findBySku(string $sku): ?Model
    {
        return $this->model->newQuery()->where('sku', $sku)->first();
    }
}