<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías principales
        $categories = [
            [
                'name' => 'Electrónica',
                'slug' => 'electronica',
                'description' => 'Dispositivos electrónicos y accesorios',
                'status' => 'active',
                'subcategories' => [
                    ['name' => 'Teléfonos', 'description' => 'Teléfonos móviles y accesorios'],
                    ['name' => 'Laptops', 'description' => 'Computadoras portátiles'],
                    ['name' => 'Tablets', 'description' => 'Tabletas digitales'],
                ]
            ],
            [
                'name' => 'Ropa y Moda',
                'slug' => 'ropa-moda',
                'description' => 'Prendas de vestir para hombres y mujeres',
                'status' => 'active',
                'subcategories' => [
                    ['name' => 'Hombres', 'description' => 'Ropa para hombres'],
                    ['name' => 'Mujeres', 'description' => 'Ropa para mujeres'],
                    ['name' => 'Accesorios', 'description' => 'Bolsas, cinturones y más'],
                ]
            ],
            [
                'name' => 'Hogar y Jardín',
                'slug' => 'hogar-jardin',
                'description' => 'Artículos para el hogar y jardín',
                'status' => 'active',
                'subcategories' => [
                    ['name' => 'Muebles', 'description' => 'Muebles y sofás'],
                    ['name' => 'Decoración', 'description' => 'Artículos decorativos'],
                    ['name' => 'Jardín', 'description' => 'Herramientas y plantas'],
                ]
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Artículos deportivos y recreativos',
                'status' => 'active',
                'subcategories' => [
                    ['name' => 'Fitness', 'description' => 'Equipos de fitness'],
                    ['name' => 'Outdoor', 'description' => 'Artículos para actividades al aire libre'],
                    ['name' => 'Calzado Deportivo', 'description' => 'Zapatillas y botas deportivas'],
                ]
            ],
            [
                'name' => 'Libros y Medios',
                'slug' => 'libros-medios',
                'description' => 'Libros, ebooks y medios digitales',
                'status' => 'active',
                'subcategories' => [
                    ['name' => 'Libros Físicos', 'description' => 'Libros impresos'],
                    ['name' => 'Ebooks', 'description' => 'Libros digitales'],
                    ['name' => 'Audiolibros', 'description' => 'Libros en formato de audio'],
                ]
            ],
        ];

        // Crear categorías principales con subcategorías
        foreach ($categories as $cat) {
            $subcats = $cat['subcategories'];
            unset($cat['subcategories']);

            // Crear categoría principal
            $parent = Category::create($cat);

            // Crear subcategorías
            foreach ($subcats as $subcat) {
                $subcat['parent_id'] = $parent->id;
                $subcat['slug'] = strtolower(str_replace(' ', '-', $subcat['name']));
                $subcat['status'] = 'active';
                Category::create($subcat);
            }
        }
    }
}
