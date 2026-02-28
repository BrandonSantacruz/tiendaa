<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'price',
        'wholesale_price',
        'stock',
        'category_id',
        'vendor_id',
        'type', // 'simple' o 'variable'
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Categoría
     * Un producto pertenece a una categoría
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Vendedor
     * Un producto pertenece a un vendedor (usuario con rol vendedor)
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Relación: Imágenes del producto
     * Un producto tiene muchas imágenes
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relación: Variantes del producto
     * Un producto puede tener muchas variantes (si es de tipo variable)
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Alcance: Solo productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Alcance: Solo productos en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Alcance: Productos de un vendedor específico
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Alcance: Productos de una categoría
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Obtener la imagen principal (primera imagen)
     */
    public function getMainImageAttribute()
    {
        return $this->images()->first()?->image_path ?? null;
    }

    /**
     * Verificar si el producto está en stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Verificar si es producto variable
     */
    public function isVariable(): bool
    {
        return $this->type === 'variable';
    }

    /**
     * Verificar si es producto simple
     */
    public function isSimple(): bool
    {
        return $this->type === 'simple';
    }

    /**
     * Generar slug automáticamente
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
