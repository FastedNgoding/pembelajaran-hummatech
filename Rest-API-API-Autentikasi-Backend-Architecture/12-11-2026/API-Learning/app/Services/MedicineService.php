<?php

namespace App\Services;

use App\Contracts\Repositories\MedicineRepositoryInterface;
use App\Contracts\Services\MedicineServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class MedicineService implements MedicineServiceInterface
{
    public function __construct(private readonly MedicineRepositoryInterface $medicines)
    {
    }

    public function list(?string $keyword, ?string $category): LengthAwarePaginator
    {
        return $this->medicines->search($keyword, $category);
    }

    public function find(int $id): Model
    {
        return $this->medicines->findById($id);
    }

    public function create(array $data): Model
    {
        $data['sku'] = strtoupper($data['sku']);

        return $this->medicines->create($data);
    }

    public function update(int $id, array $data): Model
    {
        if (isset($data['sku'])) {
            $data['sku'] = strtoupper($data['sku']);
        }

        return $this->medicines->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->medicines->delete($id);
    }
}