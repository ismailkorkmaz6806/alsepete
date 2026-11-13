<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TurkishValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_validation_errors_are_in_turkish(): void
    {
        App::setLocale('tr');

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'gecersiz-email',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Ad Soyad / İsim alanı zorunludur.',
            'email' => 'E-posta Adresi geçerli bir e-posta adresi olmalıdır.',
            'password' => 'Şifre alanı zorunludur.',
        ]);
    }

    public function test_404_error_page_renders_in_turkish(): void
    {
        $response = $this->get('/bu-adres-kesinlikle-yok-12345');

        $response->assertStatus(404);
        $response->assertSee('Sayfa Bulunamadı');
        $response->assertSee('404');
        $response->assertSee('Ana Sayfaya Git');
    }

    public function test_403_error_page_renders_in_turkish(): void
    {
        Role::firstOrCreate(['name' => 'customer']);

        $user = \App\Models\User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
        $response->assertSee('Erişim Yetkiniz Bulunmuyor');
        $response->assertSee('403');
        $response->assertSee('Bu alana yalnızca sistem yöneticileri erişebilir.');
    }
}
