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

        // Crear Vendedor Aprobado 1
        $vendedor1 = User::create([
            'name' => 'Juan Vendedor',
            'email' => 'juan@vendedor.com',
            'phone' => '34-555-1234',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'company_name' => 'Productos Juan S.L.',
            'company_description' => 'Somos una empresa especializada en productos de calidad con más de 10 años de experiencia en el mercado.',
            'tax_id' => 'ABC123456DEF',
            'address' => 'Calle Principal 123',
            'city' => 'Madrid',
            'country' => 'España',
            'postal_code' => '28001',
            'seller_status' => 'approved',
            'seller_approved_at' => now(),
            'commission_rate' => 10,
        ]);
        $vendedor1->roles()->attach($vendedorRole);

        // Crear Vendedor Aprobado 2
        $vendedor2 = User::create([
            'name' => 'María García',
            'email' => 'maria@tienda.com',
            'phone' => '34-555-5678',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'company_name' => 'Tienda Artesanal María',
            'company_description' => 'Ofrecemos productos artesanales únicos y hechos a mano con pasión y dedicación.',
            'tax_id' => 'XYZ987654ABC',
            'address' => 'Avenida Secundaria 456',
            'city' => 'Barcelona',
            'country' => 'España',
            'postal_code' => '08001',
            'seller_status' => 'approved',
            'seller_approved_at' => now(),
            'commission_rate' => 12,
        ]);
        $vendedor2->roles()->attach($vendedorRole);

        // Crear Vendedor Pendiente
        $vendedor3 = User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@negocio.com',
            'phone' => '34-555-9999',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'company_name' => 'Electrónica Carlos',
            'company_description' => 'Distribuidor de electrónica y tecnología de las mejores marcas del mercado.',
            'tax_id' => 'DEF456789XYZ',
            'address' => 'Calle Tecnología 789',
            'city' => 'Valencia',
            'country' => 'España',
            'postal_code' => '46001',
            'seller_status' => 'pending',
            'commission_rate' => 10,
        ]);
        $vendedor3->roles()->attach($vendedorRole);

        // Crear Vendedor Rechazado
        $vendedor4 = User::create([
            'name' => 'Ana Rodríguez',
            'email' => 'ana@rejected.com',
            'phone' => '34-555-3333',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'company_name' => 'Algo Raro S.A.',
            'company_description' => 'Vendedor que no cumple con los requisitos.',
            'tax_id' => 'GHI123456JKL',
            'address' => 'Calle Rechazada 999',
            'city' => 'Sevilla',
            'country' => 'España',
            'postal_code' => '41001',
            'seller_status' => 'rejected',
            'seller_rejection_reason' => 'La información de la empresa no es válida o completa.',
            'commission_rate' => 10,
        ]);
        $vendedor4->roles()->attach($vendedorRole);

        // Crear Cliente de Prueba
        $cliente = User::create([
            'name' => 'María Cliente',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $cliente->roles()->attach($clienteRole);

        // Crear más clientes
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

