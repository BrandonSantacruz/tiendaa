<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        // Super admin puede ver cualquier producto
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Vendedor solo puede ver sus propios productos
        return $user->id === $product->vendor_id;
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        // Super admin puede actualizar cualquier producto
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Vendedor solo puede actualizar sus propios productos
        return $user->id === $product->vendor_id;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        // Super admin puede eliminar cualquier producto
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Vendedor solo puede eliminar sus propios productos
        return $user->id === $product->vendor_id;
    }
}
