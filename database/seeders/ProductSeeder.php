<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener un vendedor y categorías
        $vendedores = User::whereHas('roles', function ($query) {
            $query->where('slug', 'vendedor');
        })->pluck('id')->toArray();

        if (empty($vendedores)) {
            $this->command->warn('No hay vendedores creados. Ejecuta UserSeeder primero.');
            return;
        }

        $categories = Category::whereNull('parent_id')->get();

        // Productos de ejemplo
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'sku' => 'IPHONE-15-PRO-MAX',
                'description' => 'El smartphone más potente de Apple con pantalla Super Retina XDR de 6.7 pulgadas, procesador A17 Pro y cámara avanzada.',
                'price' => 1299.99,
                'wholesale_price' => 1100.00,
                'stock' => 15,
                'type' => 'variable',
                'category_id' => $categories->where('slug', 'electronica')->first()?->id ?? 1,
                'vendor_id' => $vendedores[0],
                'status' => 'active',
                'variants' => [
                    ['name' => 'Negro Titánico - 256GB', 'sku' => 'IPHONE-15-BK-256', 'price' => 1299.99, 'stock' => 5, 'attributes' => json_encode(['color' => 'Negro Titánico', 'storage' => '256GB'])],
                    ['name' => 'Plata Blanca - 256GB', 'sku' => 'IPHONE-15-SL-256', 'price' => 1299.99, 'stock' => 5, 'attributes' => json_encode(['color' => 'Plata Blanca', 'storage' => '256GB'])],
                    ['name' => 'Oro Rosa - 256GB', 'sku' => 'IPHONE-15-GO-256', 'price' => 1299.99, 'stock' => 5, 'attributes' => json_encode(['color' => 'Oro Rosa', 'storage' => '256GB'])],
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'sku' => 'SAMSUNG-S24-ULTRA',
                'description' => 'Smartphone Android insignia con pantalla AMOLED 6.8", procesador Snapdragon 8 Gen 3 y cámara de 200MP.',
                'price' => 1299.99,
                'wholesale_price' => 1050.00,
                'stock' => 12,
                'type' => 'variable',
                'category_id' => $categories->where('slug', 'electronica')->first()?->id ?? 1,
                'vendor_id' => $vendedores[0],
                'status' => 'active',
                'variants' => [
                    ['name' => 'Gris - 256GB', 'sku' => 'SAMSUNG-S24-GR', 'price' => 1299.99, 'stock' => 6, 'attributes' => json_encode(['color' => 'Gris', 'storage' => '256GB'])],
                    ['name' => 'Blanco - 256GB', 'sku' => 'SAMSUNG-S24-WH', 'price' => 1299.99, 'stock' => 6, 'attributes' => json_encode(['color' => 'Blanco', 'storage' => '256GB'])],
                ]
            ],
            [
                'name' => 'Laptop Dell XPS 15',
                'sku' => 'DELL-XPS-15',
                'description' => 'Laptop de lujo con pantalla InfinityEdge 15.6", procesador Intel Core i7 de última generación y 32GB de RAM.',
                'price' => 2499.99,
                'wholesale_price' => 2100.00,
                'stock' => 8,
                'type' => 'simple',
                'category_id' => $categories->where('slug', 'electronica')->first()?->id ?? 1,
                'vendor_id' => $vendedores[1] ?? $vendedores[0],
                'status' => 'active',
            ],
            [
                'name' => 'Polera Básica Algodón',
                'sku' => 'POLERA-BASICA-ALG',
                'description' => 'Polera clásica de algodón 100% para hombre, cómoda y duradera.',
                'price' => 29.99,
                'wholesale_price' => 15.00,
                'stock' => 100,
                'type' => 'variable',
                'category_id' => $categories->where('slug', 'ropa-moda')->first()?->id ?? 2,
                'vendor_id' => $vendedores[1] ?? $vendedores[0],
                'status' => 'active',
                'variants' => [
                    ['name' => 'Rojo - S', 'sku' => 'POLERA-RJ-S', 'price' => 29.99, 'stock' => 20, 'attributes' => json_encode(['color' => 'Rojo', 'talla' => 'S'])],
                    ['name' => 'Rojo - M', 'sku' => 'POLERA-RJ-M', 'price' => 29.99, 'stock' => 25, 'attributes' => json_encode(['color' => 'Rojo', 'talla' => 'M'])],
                    ['name' => 'Rojo - L', 'sku' => 'POLERA-RJ-L', 'price' => 29.99, 'stock' => 25, 'attributes' => json_encode(['color' => 'Rojo', 'talla' => 'L'])],
                    ['name' => 'Azul - S', 'sku' => 'POLERA-AZ-S', 'price' => 29.99, 'stock' => 15, 'attributes' => json_encode(['color' => 'Azul', 'talla' => 'S'])],
                    ['name' => 'Negro - M', 'sku' => 'POLERA-NG-M', 'price' => 29.99, 'stock' => 20, 'attributes' => json_encode(['color' => 'Negro', 'talla' => 'M'])],
                ]
            ],
            [
                'name' => 'Zapatillas Running Nike Air Max',
                'sku' => 'NIKE-AIR-MAX',
                'description' => 'Zapatillas de running con tecnología Air Max para máxima comodidad y amortiguación.',
                'price' => 150.00,
                'wholesale_price' => 90.00,
                'stock' => 40,
                'type' => 'variable',
                'category_id' => $categories->where('slug', 'deportes')->first()?->id ?? 4,
                'vendor_id' => $vendedores[0],
                'status' => 'active',
                'variants' => [
                    ['name' => 'Negro - Talla 8', 'sku' => 'NIKE-BK-8', 'price' => 150.00, 'stock' => 10, 'attributes' => json_encode(['color' => 'Negro', 'talla' => '8'])],
                    ['name' => 'Blanco - Talla 9', 'sku' => 'NIKE-WH-9', 'price' => 150.00, 'stock' => 10, 'attributes' => json_encode(['color' => 'Blanco', 'talla' => '9'])],
                    ['name' => 'Azul - Talla 10', 'sku' => 'NIKE-BL-10', 'price' => 150.00, 'stock' => 10, 'attributes' => json_encode(['color' => 'Azul', 'talla' => '10'])],
                    ['name' => 'Rojo - Talla 11', 'sku' => 'NIKE-RD-11', 'price' => 150.00, 'stock' => 10, 'attributes' => json_encode(['color' => 'Rojo', 'talla' => '11'])],
                ]
            ],
            [
                'name' => 'El Quijote de Miguel de Cervantes',
                'sku' => 'LIBRO-QUIJOTE',
                'description' => 'Novela clásica de la literatura española, edición de bolsillo.',
                'price' => 19.99,
                'wholesale_price' => 10.00,
                'stock' => 50,
                'type' => 'simple',
                'category_id' => $categories->where('slug', 'libros-medios')->first()?->id ?? 5,
                'vendor_id' => $vendedores[0],
                'status' => 'active',
            ],
            [
                'name' => 'Escritorio Gaming RGB',
                'sku' => 'DESK-GAMING-RGB',
                'description' => 'Escritorio para gaming con iluminación RGB, superficie grande y cable management integrado.',
                'price' => 399.99,
                'wholesale_price' => 250.00,
                'stock' => 10,
                'type' => 'simple',
                'category_id' => $categories->where('slug', 'hogar-jardin')->first()?->id ?? 3,
                'vendor_id' => $vendedores[1] ?? $vendedores[0],
                'status' => 'active',
            ],
        ];

        // Crear productos y variantes
        foreach ($products as $productData) {
            $variants = $productData['variants'] ?? [];
            unset($productData['variants']);
            
            // Agregar slug si no existe
            if (!isset($productData['slug'])) {
                $productData['slug'] = strtolower(str_replace(' ', '-', $productData['name']));
            }

            // Crear producto
            $product = Product::create($productData);

            // Crear variantes si existen
            foreach ($variants as $variant) {
                $variant['product_id'] = $product->id;
                $variant['status'] = 'active';
                ProductVariant::create($variant);
            }
        }

        $this->command->info('✓ ' . Product::count() . ' productos creados exitosamente.');
    }
}
