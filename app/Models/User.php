<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un usuario tiene muchos roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Relación: Un usuario tiene muchos permisos a través de sus roles
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->through('roles');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Verificar si el usuario tiene uno de los roles especificados
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Verificar si el usuario tiene todos los roles especificados
     */
    public function hasAllRoles(array $roles): bool
    {
        return count($roles) === $this->roles()
            ->whereIn('slug', $roles)
            ->count();
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Obtener el rol principal del usuario
     */
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->first();
    }

    /**
     * RELACIONES MARKETPLACE
     */

    /**
     * Productos del vendedor
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    /**
     * Comisiones del vendedor
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'seller_id');
    }

    /**
     * Pagos del vendedor
     */
    public function payouts()
    {
        return $this->hasMany(SellerPayout::class, 'seller_id');
    }

    /**
     * MÉTODOS MARKETPLACE
     */

    /**
     * Verificar si el usuario es vendedor aprobado
     */
    public function isApprovedSeller(): bool
    {
        return $this->seller_status === 'approved' && $this->hasRole('vendedor');
    }

    /**
     * Verificar si el usuario solicitud pendiente de vendedor
     */
    public function isPendingSeller(): bool
    {
        return $this->seller_status === 'pending';
    }

    /**
     * Verificar si el usuario es vendedor rechazado
     */
    public function isRejectedSeller(): bool
    {
        return $this->seller_status === 'rejected';
    }

    /**
     * Verificar si el usuario es vendedor suspendido
     */
    public function isSuspendedSeller(): bool
    {
        return $this->seller_status === 'suspended';
    }

    /**
     * Obtener comisión pendiente total
     */
    public function getPendingCommissionTotal(): float
    {
        return $this->commissions()
            ->where('status', 'pending')
            ->sum('commission_amount') ?? 0;
    }

    /**
     * Obtener ingresos totales del mes
     */
    public function getMonthlyEarnings(): float
    {
        return $this->commissions()
            ->whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->sum('sale_amount') ?? 0;
    }

    /**
     * Obtener comisiones ganadas total
     */
    public function getTotalCommissions(): float
    {
        return $this->commissions()
            ->where('status', '!=', 'refunded')
            ->sum('commission_amount') ?? 0;
    }
}

