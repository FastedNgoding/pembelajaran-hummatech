<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends BaseController
{
    public function __construct(private readonly UserServiceInterface $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->service->list($request->query('search'), $request->query('role'))
        );
    }

    public function show(int $id): JsonResponse
    {
        return $this->success(new UserResource($this->service->find($id)));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->created(new UserResource($this->service->create($request->validated())));
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->success(
            new UserResource($this->service->update($id, $request->validated())),
            'Data user berhasil diubah'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->success(null, 'User berhasil dihapus');
    }
}