<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerPayout extends Model
{
    protected $table = 'seller_payouts';

    protected $fillable = [
        'seller_id',
        'amount',
        'commission_count',
        'payment_method',
        'payment_reference',
        'status',
        'notes',
        'period_start',
        'period_end',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
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
     * Alcance: Pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Alcance: Pagos completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Alcance: Pagos en proceso
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Alcance: Del mes actual
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('period_start', now()->month)
            ->whereYear('period_start', now()->year);
    }

    /**
     * Marcar como procesado
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);
    }

    /**
     * Marcar como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Obtener URL de descarga del comprobante (si existe)
     */
    public function getProofUrl(): ?string
    {
        if (!$this->payment_reference) {
            return null;
        }

        return match ($this->payment_method) {
            'bank_transfer' => route('seller.payouts.proof', $this->id),
            'check' => route('seller.payouts.proof', $this->id),
            default => null,
        };
    }
}
