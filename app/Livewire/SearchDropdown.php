<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class SearchDropdown extends Component
{
    public string $query = '';

    public function resetSearch(): void
    {
        $this->query = '';
    }

    public function render()
    {
        $searchResults = collect();
        $matchingBrands = collect();
        $matchingCategories = collect();

        $cleanQuery = trim($this->query);

        if (mb_strlen($cleanQuery) >= 2) {
            $searchResults = Product::with(['brand', 'variants'])
                ->where('status', 'aktif')
                ->where(function ($q) use ($cleanQuery) {
                    $q->where('name', 'like', "%{$cleanQuery}%")
                        ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$cleanQuery}%"));
                })
                ->take(5)
                ->get();

            $matchingBrands = Brand::where('name', 'like', "%{$cleanQuery}%")
                ->take(3)
                ->get();

            $matchingCategories = Category::where('name', 'like', "%{$cleanQuery}%")
                ->take(3)
                ->get();
        }

        return view('livewire.search-dropdown', [
            'products' => $searchResults,
            'brands' => $matchingBrands,
            'categories' => $matchingCategories,
        ]);
    }
}
