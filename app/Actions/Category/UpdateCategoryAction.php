<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategoryAction
{
    /**
     * Mevcut kategoriyi günceller.
     */
    public function execute(Category $category, array $data): Category
    {
        $slug = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Eğer slug değiştiyse benzersizliği kontrol et
        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }
        }

        $category->update([
            'parent_id' => ! empty($data['parent_id']) ? $data['parent_id'] : null,
            'name' => $data['name'],
            'slug' => $slug,
            'commission_rate' => $data['commission_rate'] ?? $category->commission_rate,
            'sort' => isset($data['sort']) ? (int) $data['sort'] : $category->sort,
        ]);

        return $category;
    }
}
