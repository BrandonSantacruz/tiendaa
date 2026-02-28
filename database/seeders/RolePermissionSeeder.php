<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Roles
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Administrador del sistema con acceso total',
        ]);

        $vendedor = Role::create([
            'name' => 'Vendedor',
            'slug' => 'vendedor',
            'description' => 'Usuario que puede vender productos',
        ]);

        $cliente = Role::create([
            'name' => 'Cliente',
            'slug' => 'cliente',
            'description' => 'Usuario cliente que puede comprar productos',
        ]);

        // Crear Permisos para Super Admin
        $permissions = [
            // Usuarios
            ['name' => 'Ver Usuarios', 'slug' => 'view-users', 'description' => 'Puede ver lista de usuarios'],
            ['name' => 'Crear Usuario', 'slug' => 'create-user', 'description' => 'Puede crear nuevos usuarios'],
            ['name' => 'Editar Usuario', 'slug' => 'edit-user', 'description' => 'Puede editar datos de usuarios'],
            ['name' => 'Eliminar Usuario', 'slug' => 'delete-user', 'description' => 'Puede eliminar usuarios'],

            // Roles
            ['name' => 'Ver Roles', 'slug' => 'view-roles', 'description' => 'Puede ver lista de roles'],
            ['name' => 'Crear Rol', 'slug' => 'create-role', 'description' => 'Puede crear nuevos roles'],
            ['name' => 'Editar Rol', 'slug' => 'edit-role', 'description' => 'Puede editar roles'],
            ['name' => 'Eliminar Rol', 'slug' => 'delete-role', 'description' => 'Puede eliminar roles'],

            // Permisos
            ['name' => 'Ver Permisos', 'slug' => 'view-permissions', 'description' => 'Puede ver lista de permisos'],
            ['name' => 'Crear Permiso', 'slug' => 'create-permission', 'description' => 'Puede crear nuevos permisos'],
            ['name' => 'Editar Permiso', 'slug' => 'edit-permission', 'description' => 'Puede editar permisos'],
            ['name' => 'Eliminar Permiso', 'slug' => 'delete-permission', 'description' => 'Puede eliminar permisos'],

            // Productos (gerenciar todos)
            ['name' => 'Ver Todos Productos', 'slug' => 'view-all-products', 'description' => 'Puede ver todos los productos'],
            ['name' => 'Eliminar Producto', 'slug' => 'delete-product', 'description' => 'Puede eliminar cualquier producto'],

            // Órdenes
            ['name' => 'Ver Todas Órdenes', 'slug' => 'view-all-orders', 'description' => 'Puede ver todas las órdenes'],

            // Reportes
            ['name' => 'Ver Reportes', 'slug' => 'view-reports', 'description' => 'Puede ver reportes del sistema'],
            ['name' => 'Generar Reportes', 'slug' => 'generate-reports', 'description' => 'Puede generar reportes personalizados'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Asignar todos los permisos al Super Admin
        $allPermissions = Permission::all();
        $superAdmin->permissions()->sync($allPermissions->pluck('id'));

        // Crear Permisos para Vendedor
        $vendedorPermissions = [
            ['name' => 'Ver Mis Productos Vendedor', 'slug' => 'view-own-products', 'description' => 'Puede ver sus propios productos'],
            ['name' => 'Crear Producto Vendedor', 'slug' => 'create-product', 'description' => 'Puede crear productos'],
            ['name' => 'Editar Producto Vendedor', 'slug' => 'edit-product', 'description' => 'Puede editar sus productos'],
            ['name' => 'Eliminar Producto Vendedor', 'slug' => 'delete-own-product', 'description' => 'Puede eliminar sus productos'],
            ['name' => 'Ver Mis Órdenes', 'slug' => 'view-own-orders', 'description' => 'Puede ver sus órdenes'],
            ['name' => 'Ver Mis Reportes', 'slug' => 'view-own-reports', 'description' => 'Puede ver sus reportes de ventas'],
        ];

        foreach ($vendedorPermissions as $permission) {
            Permission::create($permission);
        }

        // Asignar permisos al Vendedor
        $vendedor->permissions()->sync(
            Permission::whereIn('slug', [
                'view-own-products',
                'create-product',
                'edit-product',
                'delete-own-product',
                'view-own-orders',
                'view-own-reports',
            ])->pluck('id')
        );

        // Crear Permisos para Cliente
        $clientePermissions = [
            ['name' => 'Ver Catálogo', 'slug' => 'view-catalog', 'description' => 'Puede ver el catálogo de productos'],
            ['name' => 'Crear Orden', 'slug' => 'create-order', 'description' => 'Puede crear órdenes'],
            ['name' => 'Ver Mis Órdenes Cliente', 'slug' => 'view-own-orders-client', 'description' => 'Puede ver sus compras'],
            ['name' => 'Calificar Producto', 'slug' => 'rate-product', 'description' => 'Puede calificar productos'],
        ];

        foreach ($clientePermissions as $permission) {
            Permission::create($permission);
        }

        // Asignar permisos al Cliente
        $cliente->permissions()->sync(
            Permission::whereIn('slug', [
                'view-catalog',
                'create-order',
                'view-own-orders-client',
                'rate-product',
            ])->pluck('id')
        );
    }
}

