<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero crear roles y permisos
        $this->call(RolePermissionSeeder::class);
        
        // Luego crear usuarios
        $this->call(UserSeeder::class);
        
        // Crear categorías
        $this->call(CategorySeeder::class);
        
        // Crear productos
        $this->call(ProductSeeder::class);
    }
}
