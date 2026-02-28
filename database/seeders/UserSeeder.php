<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $vendedorRole = Role::where('slug', 'vendedor')->first();
        $clienteRole = Role::where('slug', 'cliente')->first();

        // Crear Super Admin
        $superAdmin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->roles()->attach($superAdminRole);

        // Crear Vendedor de Prueba
        $vendedor = User::create([
            'name' => 'Juan Vendedor',
            'email' => 'vendedor@tienda.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vendedor->roles()->attach($vendedorRole);

        // Crear Cliente de Prueba
        $cliente = User::create([
            'name' => 'María Cliente',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $cliente->roles()->attach($clienteRole);

        // Crear más usuarios de prueba
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Cliente Prueba $i",
                'email' => "cliente$i@tienda.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $user->roles()->attach($clienteRole);
        }

        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Vendedor Prueba $i",
                'email' => "vendedor$i@tienda.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $user->roles()->attach($vendedorRole);
        }
    }
}

