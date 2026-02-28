<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Relación: Un permiso pertenece a muchos roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
