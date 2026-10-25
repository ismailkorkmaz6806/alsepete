<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    /**
     * Yeni bir kategori oluşturur.
     */
    public function execute(array $data): Category
    {
        $slug = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Benzersiz slug üretimi
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $sort = isset($data['sort']) && is_numeric($data['sort'])
            ? (int) $data['sort']
            : (Category::where('parent_id', $data['parent_id'] ?? null)->max('sort') + 1);

        return Category::create([
            'parent_id' => ! empty($data['parent_id']) ? $data['parent_id'] : null,
            'name' => $data['name'],
            'slug' => $slug,
            'commission_rate' => $data['commission_rate'] ?? 10.00,
            'sort' => $sort,
        ]);
    }
}
