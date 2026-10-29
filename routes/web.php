<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CategoryShowController;
use App\Http\Controllers\ProductShowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\QuestionController as SellerQuestionController;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::whereNull('parent_id')->with('children')->orderBy('sort')->get();

    // Hafta 3 Vitrin Bolumleri (N+1 Onleme: with() eager loading)
    $bestsellers = Product::with(['seller', 'category', 'brand', 'variants', 'media'])
        ->where('status', 'aktif')
        ->orderByDesc('id')
        ->take(8)
        ->get();

    $newArrivals = Product::with(['seller', 'category', 'brand', 'variants', 'media'])
        ->where('status', 'aktif')
        ->latest()
        ->take(8)
        ->get();

    $banners = Banner::where('is_active', true)->orderBy('sort')->get();

    return view('welcome', compact('categories', 'bestsellers', 'newArrivals', 'banners'));
})->name('home');

// Hafta 3 SEO Uyumlu Rotalar
Route::get('/urun/{slug}', [ProductShowController::class, 'show'])->name('products.show');
Route::get('/kategori/{slug}', [CategoryShowController::class, 'show'])->name('categories.show');

Route::get('/dil/{locale}', function (string $locale) {
    if (in_array($locale, ['tr', 'en'])) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 30));
    }

    return redirect()->back(fallback: route('home'));
})->name('locale.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Paneli Rotalari
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
});

// Satici Paneli Rotalari
Route::middleware(['auth', 'seller.approved'])->prefix('satici')->name('seller.')->group(function () {
    Route::get('/', function () {
        return view('seller.dashboard');
    })->name('dashboard');

    // Bonus 2: Satıcı Soru & Cevap Yönetimi
    Route::get('/sorular', [SellerQuestionController::class, 'index'])->name('questions.index');
    Route::put('/sorular/{question}', [SellerQuestionController::class, 'update'])->name('questions.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
