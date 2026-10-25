<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('categories')) {
                    $categories = Category::whereNull('parent_id')
                        ->with(['children' => fn ($q) => $q->orderBy('sort')])
                        ->orderBy('sort')
                        ->get();
                    $view->with('globalCategories', $categories);
                }
            } catch (\Throwable $e) {
                // Ignore during early setup
            }
        });
    }
}
