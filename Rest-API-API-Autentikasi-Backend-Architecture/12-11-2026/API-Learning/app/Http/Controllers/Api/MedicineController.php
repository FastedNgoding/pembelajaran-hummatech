<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\MedicineServiceInterface;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Resources\MedicineResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MedicineController extends BaseController
{
    public function __construct(private readonly MedicineServiceInterface $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return MedicineResource::collection(
            $this->service->list($request->query('search'), $request->query('category'))
        );
    }

    public function show(int $id): JsonResponse
    {
        return $this->success(new MedicineResource($this->service->find($id)));
    }

    public function store(StoreMedicineRequest $request): JsonResponse
    {
        return $this->created(new MedicineResource($this->service->create($request->validated())));
    }

    public function update(StoreMedicineRequest $request, int $id): JsonResponse
    {
        return $this->success(
            new MedicineResource($this->service->update($id, $request->validated())),
            'Data berhasil diubah'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->success(null, 'Data berhasil dihapus');
    }
}