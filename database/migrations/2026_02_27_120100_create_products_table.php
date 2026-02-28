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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            
            // Precios
            $table->decimal('price', 10, 2);
            $table->decimal('wholesale_price', 10, 2)->nullable();
            
            // Stock
            $table->integer('stock')->default(0);
            
            // Relaciones
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            
            // Tipo: simple o variable
            $table->enum('type', ['simple', 'variable'])->default('simple');
            
            // Estado: active, inactive, archived
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index('category_id');
            $table->index('vendor_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
