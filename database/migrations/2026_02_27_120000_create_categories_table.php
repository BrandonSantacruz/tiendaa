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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            
            // Para subcategorías
            $table->foreignId('parent_id')->nullable()->constrained(
                table: 'categories',
                column: 'id'
            )->onDelete('cascade');
            
            // Estado: active, inactive
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index('parent_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
