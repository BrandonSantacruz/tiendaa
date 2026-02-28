<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->unique();
            
            // Precios específicos de la variante
            $table->decimal('price', 10, 2);
            $table->decimal('wholesale_price', 10, 2)->nullable();
            
            // Stock de la variante
            $table->integer('stock')->default(0);
            
            // Atributos en JSON: {"color": "rojo", "size": "M"}
            $table->json('attributes')->nullable();
            
            // Imagen específica de la variante
            $table->string('image_path')->nullable();
            
            // Estado
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();

            // Índices
            $table->index('product_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
