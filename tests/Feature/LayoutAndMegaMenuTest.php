<?php

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('home page renders mega menu with categories from database', function () {
    $parent = Category::create([
        'name' => 'Elektronik & Bilgisayar',
        'slug' => 'elektronik-bilgisayar',
        'commission_rate' => 10.00,
        'sort' => 1,
    ]);

    $child = Category::create([
        'parent_id' => $parent->id,
        'name' => 'Dizüstü Bilgisayarlar',
        'slug' => 'dizustu-bilgisayarlar',
        'commission_rate' => 8.00,
        'sort' => 1,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Elektronik & Bilgisayar');
    $response->assertSee('Dizüstü Bilgisayarlar');
    $response->assertSee('PazarYeri');
    $response->assertSee('Sepetim');
});

test('authenticated user sees account menu with their name', function () {
    Role::findOrCreate('customer', 'web');
    $user = User::factory()->create([
        'name' => 'Mehmet Demir',
        'email' => 'mehmet@example.com',
    ]);
    $user->assignRole('customer');

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Mehmet Demir');
    $response->assertSee('Profil Ayarları');
});

test('admin sees admin panel link in account menu', function () {
    Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Admin Yönetim Paneli');
});
