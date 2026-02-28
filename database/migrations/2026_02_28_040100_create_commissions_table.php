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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            
            // Detalles de la comisión
            $table->decimal('sale_amount', 10, 2);      // Monto de venta
            $table->decimal('commission_rate', 5, 2);   // % de comisión aplicada
            $table->decimal('commission_amount', 10, 2);// Monto de comisión
            
            // Estado
            $table->enum('status', ['pending', 'approved', 'paid', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            
            // Fechas
            $table->timestamp('sale_date');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();

            // Índices
            $table->index('seller_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('sale_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
