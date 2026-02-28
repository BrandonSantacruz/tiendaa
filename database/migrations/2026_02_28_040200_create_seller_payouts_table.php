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
        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            
            // Detalles del pago
            $table->decimal('amount', 10, 2);
            $table->integer('commission_count');    // Cantidad de comisiones
            
            // Método de pago
            $table->enum('payment_method', ['bank_transfer', 'check', 'paypal', 'stripe'])->default('bank_transfer');
            $table->string('payment_reference')->nullable();
            
            // Estado
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            // Período
            $table->date('period_start');
            $table->date('period_end');
            
            // Fechas
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();

            // Índices
            $table->index('seller_id');
            $table->index('status');
            $table->index('period_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_payouts');
    }
};
