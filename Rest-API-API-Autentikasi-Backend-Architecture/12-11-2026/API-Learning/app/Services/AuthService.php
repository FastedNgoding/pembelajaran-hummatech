<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Enums\UserRole;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService implements AuthServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function register(array $data): array
    {
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => UserRole::Kasir->value,
        ]);

        return $this->tokenPayload(auth('api')->login($user));
    }

    public function login(array $credentials): array
    {
        $token = auth('api')->attempt($credentials);

        if (! $token) {
            throw new AuthenticationException('Email atau password salah');
        }

        return $this->tokenPayload($token);
    }

    public function refresh(): array
    {
        return $this->tokenPayload(auth('api')->refresh());
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function me(): Authenticatable
    {
        return auth('api')->user();
    }

    private function tokenPayload(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ];
    }
}