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
        Schema::table('users', function (Blueprint $table) {
            // Campos para marketplace/vendedor
            $table->string('phone')->nullable()->after('email');
            $table->string('company_name')->nullable()->after('phone');
            $table->text('company_description')->nullable()->after('company_name');
            $table->string('company_logo')->nullable()->after('company_description');
            $table->string('tax_id')->nullable()->unique()->after('company_logo');
            
            // Dirección
            $table->string('address')->nullable()->after('tax_id');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('country');
            
            // Verificación y estado
            $table->enum('seller_status', ['pending', 'approved', 'rejected', 'suspended'])->nullable()->after('postal_code');
            $table->text('seller_rejection_reason')->nullable()->after('seller_status');
            $table->timestamp('seller_approved_at')->nullable()->after('seller_rejection_reason');
            
            // Comisión por defecto
            $table->decimal('commission_rate', 5, 2)->default(10)->after('seller_approved_at');
            
            // Índices
            $table->index('seller_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['seller_status']);
            $table->dropColumn([
                'phone',
                'company_name',
                'company_description',
                'company_logo',
                'tax_id',
                'address',
                'city',
                'country',
                'postal_code',
                'seller_status',
                'seller_rejection_reason',
                'seller_approved_at',
                'commission_rate',
            ]);
        });
    }
};
