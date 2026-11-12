<?php

namespace App\Providers;

use App\Contracts\Repositories\MedicineRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\MedicineServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Repositories\MedicineRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\MedicineService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        MedicineRepositoryInterface::class => MedicineRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        MedicineServiceInterface::class => MedicineService::class,
        AuthServiceInterface::class => AuthService::class,
        UserServiceInterface::class => UserService::class,
    ];
}