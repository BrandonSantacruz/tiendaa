<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Categoría padre
     * Una categoría puede pertenecer a otra categoría (para subcategorías)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relación: Subcategorías
     * Una categoría puede tener muchas subcategorías
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relación: Productos
     * Una categoría tiene muchos productos
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Alcance: Solo categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Alcance: Solo categorías principales (sin padre)
     */
    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Obtener ruta slug
     */
    public function getSlugAttribute()
    {
        return $this->attributes['slug'] ?? strtolower(str_replace(' ', '-', $this->name));
    }

    /**
     * Generar slug automáticamente antes de guardar
     */
    protected static function booting()
    {
        static::creating(function ($model) {
            if (!isset($model->slug)) {
                $model->slug = strtolower(str_replace(' ', '-', $model->name));
            }
        });
    }
}
