<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function list(?string $keyword, ?string $role): LengthAwarePaginator
    {
        return $this->users->search($keyword, $role);
    }

    public function find(int $id): Model
    {
        return $this->users->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->users->create($data);
    }

    public function update(int $id, array $data): Model
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->users->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($id === auth('api')->id()) {
            throw new AuthorizationException('Tidak bisa menghapus akun sendiri');
        }

        $this->users->delete($id);
    }
}