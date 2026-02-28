<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    protected $fillable = [
        'seller_id',
        'product_id',
        'order_id',
        'sale_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'notes',
        'sale_date',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'sale_date' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Vendedor
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relación: Producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Alcance: Comisiones pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Alcance: Comisiones aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Alcance: Comisiones pagadas
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Alcance: Del mes actual
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year);
    }

    /**
     * Obtener comisiones de un vendedor en un período
     */
    public function scopeBySellerAndPeriod($query, $sellerId, $startDate, $endDate)
    {
        return $query->where('seller_id', $sellerId)
            ->whereBetween('sale_date', [$startDate, $endDate]);
    }
}
