<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SellerAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    }

    public function test_customer_receives_403_when_accessing_seller_panel(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $response = $this->actingAs($customer)->get('/satici');
        $response->assertStatus(403);
    }

    public function test_pending_seller_receives_403_when_accessing_seller_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Test Magaza',
            'slug' => 'test-magaza',
            'tax_number' => '1234567890',
            'iban' => 'TR123456789012345678901234',
            'status' => 'beklemede',
        ]);

        $response = $this->actingAs($user)->get('/satici');
        $response->assertStatus(403);
    }

    public function test_approved_seller_can_access_seller_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Onayli Magaza',
            'slug' => 'onayli-magaza',
            'tax_number' => '9876543210',
            'iban' => 'TR987654321098765432109876',
            'status' => 'onayli',
        ]);

        $response = $this->actingAs($user)->get('/satici');
        $response->assertStatus(200);
    }
}
