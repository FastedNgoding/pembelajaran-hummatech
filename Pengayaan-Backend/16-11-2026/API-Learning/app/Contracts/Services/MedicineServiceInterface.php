<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface MedicineServiceInterface
{
    public function list(?string $keyword, ?string $category): LengthAwarePaginator;

    public function find(int $id): Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): void;
}