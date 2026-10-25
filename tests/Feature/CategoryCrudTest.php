<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->customer = User::factory()->create();
        $this->customer->assignRole('customer');
    }

    public function test_admin_can_view_category_list(): void
    {
        Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'commission_rate' => 8.00,
            'sort' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Elektronik');
    }

    public function test_admin_can_create_category_with_auto_slug(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Moda & Giyim',
            'commission_rate' => 12.50,
            'sort' => 2,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Moda & Giyim',
            'slug' => 'moda-giyim',
            'commission_rate' => 12.50,
        ]);
    }

    public function test_admin_can_create_sub_category(): void
    {
        $parent = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'commission_rate' => 8.00,
            'sort' => 1,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Akıllı Telefonlar',
            'parent_id' => $parent->id,
            'commission_rate' => 6.00,
            'sort' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Akıllı Telefonlar',
            'parent_id' => $parent->id,
            'slug' => 'akilli-telefonlar',
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Kitap',
            'slug' => 'kitap',
            'commission_rate' => 5.00,
            'sort' => 1,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Kitap & Hobi',
            'commission_rate' => 7.00,
            'sort' => 3,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kitap & Hobi',
            'slug' => 'kitap-hobi',
            'commission_rate' => 7.00,
        ]);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::create([
            'name' => 'Geçici Kategori',
            'slug' => 'gecici-kategori',
            'commission_rate' => 10.00,
            'sort' => 99,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_customer_cannot_manage_categories(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.categories.index'));
        $response->assertStatus(403);

        $responsePost = $this->actingAs($this->customer)->post(route('admin.categories.store'), [
            'name' => 'Yetkisiz Kategori',
            'commission_rate' => 10.00,
        ]);
        $responsePost->assertStatus(403);
    }

    public function test_category_validation_requires_name_and_commission(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => '',
            'commission_rate' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'commission_rate']);
    }
}
