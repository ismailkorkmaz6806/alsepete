<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('seller') && $user->seller?->status === 'onayli');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Product $product): bool
    {
        if ($product->status === 'aktif') {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->seller && (int) $user->seller->id === (int) $product->seller_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('seller') && $user->seller && in_array($user->seller->status, ['onayli', 'approved']);
    }

    /**
     * Determine whether the user can update the model.
     * Hafta 4 Kabul Kriteri: Satıcı A, Satıcı B'nin ürün düzenleme adresini elle yazarsa 403 alır.
     */
    public function update(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('seller')
            && $user->seller
            && in_array($user->seller->status, ['onayli', 'approved'])
            && (int) $user->seller->id === (int) $product->seller_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('seller')
            && $user->seller
            && in_array($user->seller->status, ['onayli', 'approved'])
            && (int) $user->seller->id === (int) $product->seller_id;
    }
}
