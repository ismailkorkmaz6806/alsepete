<?php

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::whereNull('parent_id')->with('children')->orderBy('sort')->get();
    $products = Product::with(['seller', 'category', 'brand', 'variants'])->where('status', 'aktif')->take(8)->get();
    $banners = Banner::where('is_active', true)->orderBy('sort')->get();

    return view('welcome', compact('categories', 'products', 'banners'));
});
