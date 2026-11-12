<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    public function __construct(private readonly AuthServiceInterface $service)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->created($this->service->register($request->validated()), 'Registrasi berhasil');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->success($this->service->login($request->validated()), 'Login berhasil');
    }

    public function me(): JsonResponse
    {
        return $this->success(new UserResource($this->service->me()));
    }

    public function refresh(): JsonResponse
    {
        return $this->success($this->service->refresh(), 'Token diperbarui');
    }

    public function logout(): JsonResponse
    {
        $this->service->logout();

        return $this->success(null, 'Berhasil logout');
    }
}