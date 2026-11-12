<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthServiceInterface
{
    public function register(array $data): array;

    public function login(array $credentials): array;

    public function refresh(): array;

    public function logout(): void;

    public function me(): Authenticatable;
}