<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Apoteker Admin',
            'email' => 'admin@apotek.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        Medicine::create([
            'sku' => 'PCT-500',
            'name' => 'Paracetamol 500mg',
            'category' => 'Analgesik',
            'price' => 5000,
            'stock' => 200,
            'requires_prescription' => false,
            'expired_at' => now()->addYears(2),
        ]);
    }
}
