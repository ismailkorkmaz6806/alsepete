<?php

namespace App\Actions\Category;

use App\Models\Category;

class DeleteCategoryAction
{
    /**
     * Kategoriyi siler ve alt kategorilerinin ilişkisini düzenler.
     */
    public function execute(Category $category): bool
    {
        // Alt kategorilerin parent_id'sini null yaparak yetim kalmalarını sağla veya üst kategoriye taşı
        $category->children()->update(['parent_id' => $category->parent_id]);

        return (bool) $category->delete();
    }
}
