<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class TurkishValidationTest extends TestCase
{
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
}
