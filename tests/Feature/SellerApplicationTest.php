<?php

use App\Models\Seller;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'seller']);
    Role::firstOrCreate(['name' => 'customer']);

    $this->customer = User::factory()->create();
    $this->customer->assignRole('customer');

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('misafir kullanici satici basvuru sayfasina erismek istediginde giris ekranina yonlendirilir', function () {
    $response = $this->get(route('seller.apply'));
    $response->assertRedirect(route('login'));
});

test('musteri satici basvuru formunu goruntuleyebilir ve basvuru yapabilir', function () {
    $response = $this->actingAs($this->customer)->get(route('seller.apply'));
    $response->assertStatus(200);
    $response->assertSee('Mağazanızı Açın');

    // Başvuru gönder
    $postResponse = $this->actingAs($this->customer)->post(route('seller.apply.store'), [
        'store_name' => 'Yeni Teknoloji Dünyası',
        'tax_number' => '1234567890',
        'iban' => 'TR123456789012345678901234',
    ]);

    $postResponse->assertRedirect(route('seller.apply'));
    $postResponse->assertSessionHas('success');

    $seller = Seller::where('user_id', $this->customer->id)->first();
    expect($seller)->not->toBeNull();
    expect($seller->store_name)->toBe('Yeni Teknoloji Dünyası');
    expect($seller->status)->toBe('beklemede');
});

test('durumu beklemede olan satici adayi /satici paneline erisemez 403 alir', function () {
    Seller::create([
        'user_id' => $this->customer->id,
        'store_name' => 'Bekleyen Magaza',
        'slug' => 'bekleyen-magaza',
        'tax_number' => '9999999999',
        'iban' => 'TR999999999999999999999999',
        'status' => 'beklemede',
        'commission_rate' => 10.00,
    ]);

    $response = $this->actingAs($this->customer)->get(route('seller.dashboard'));
    $response->assertStatus(403);
});

test('admin bekleyen satıcı başvurusunu onaylayabilir ve yetkilendirebilir', function () {
    $pendingSeller = Seller::create([
        'user_id' => $this->customer->id,
        'store_name' => 'Onaylanacak Magaza',
        'slug' => 'onaylanacak-magaza',
        'tax_number' => '8888888888',
        'iban' => 'TR888888888888888888888888',
        'status' => 'beklemede',
        'commission_rate' => 10.00,
    ]);

    // Admin listeyi görür
    $listResponse = $this->actingAs($this->admin)->get(route('admin.sellers.index'));
    $listResponse->assertStatus(200);
    $listResponse->assertSee('Onaylanacak Magaza');

    // Admin onaylar
    $approveResponse = $this->actingAs($this->admin)->post(route('admin.sellers.approve', $pendingSeller));
    $approveResponse->assertSessionHas('success');

    expect($pendingSeller->fresh()->status)->toBe('onayli');
    expect($this->customer->fresh()->hasRole('seller'))->toBeTrue();

    // Artık satıcı paneline girebilir
    $panelResponse = $this->actingAs($this->customer->fresh())->get(route('seller.dashboard'));
    $panelResponse->assertStatus(200);
});
