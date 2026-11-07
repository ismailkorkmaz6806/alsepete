<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\CategoryShowController;
use App\Http\Controllers\ProductShowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\QuestionController as SellerQuestionController;
use App\Http\Controllers\SellerApplicationController;
use App\Http\Controllers\StoreShowController;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductQuestion;
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

// Hafta 3 & Hafta 4 SEO Uyumlu Rotalar
Route::get('/urun/{slug}', [ProductShowController::class, 'show'])->name('products.show');
Route::get('/kategori/{slug}', [CategoryShowController::class, 'show'])->name('categories.show');
Route::get('/arama', [SearchController::class, 'index'])->name('search');
Route::get('/magaza/{slug}', [StoreShowController::class, 'show'])->name('stores.show');

// Satıcı Ol Başvuru Rotaları
Route::middleware('auth')->group(function () {
    Route::get('/satici-ol', [SellerApplicationController::class, 'create'])->name('seller.apply');
    Route::post('/satici-ol', [SellerApplicationController::class, 'store'])->name('seller.apply.store');
});

Route::get('/dil/{locale}', function (string $locale) {
    if (in_array($locale, ['tr', 'en'])) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 30));
    }

    return redirect()->back(fallback: route('home'));
})->name('locale.switch');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $myQuestions = ProductQuestion::with(['product.category', 'seller'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    return view('dashboard', compact('user', 'myQuestions'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Paneli Rotalari
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);

    // Hafta 4: Satıcı Başvuruları & Yönetimi
    Route::get('/saticilar', [SellerManagementController::class, 'index'])->name('sellers.index');
    Route::post('/saticilar/{seller}/onayla', [SellerManagementController::class, 'approve'])->name('sellers.approve');
    Route::post('/saticilar/{seller}/reddet', [SellerManagementController::class, 'reject'])->name('sellers.reject');
});

// Satici Paneli Rotalari
Route::middleware(['auth', 'seller.approved'])->prefix('satici')->name('seller.')->group(function () {
    Route::get('/', function () {
        return view('seller.dashboard');
    })->name('dashboard');

    // Hafta 4: Ürün Yönetimi (CRUD, Hızlı Stok, Aktif/Pasif)
    Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
    Route::get('/urunler/yeni', [ProductController::class, 'create'])->name('products.create');
    Route::post('/urunler', [ProductController::class, 'store'])->name('products.store');
    Route::get('/urunler/{product}/duzenle', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/urunler/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/urunler/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/urunler/{product}/stok', [ProductController::class, 'updateStock'])->name('products.stock');
    Route::patch('/urunler/{product}/durum', [ProductController::class, 'toggleStatus'])->name('products.status');

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
