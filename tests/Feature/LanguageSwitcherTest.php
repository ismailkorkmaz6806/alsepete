<?php

test('locale can be switched to english', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'en']));

    $response->assertSessionHas('locale', 'en');
});

test('locale can be switched to turkish', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'tr']));

    $response->assertSessionHas('locale', 'tr');
});

test('invalid locale is ignored', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'fr']));

    $response->assertSessionMissing('locale');
});

test('home page renders in english when locale is set to en', function () {
    $response = $this->withSession(['locale' => 'en'])->get('/');

    $response->assertStatus(200);
    $response->assertSee('All Categories');
    $response->assertSee('Become a Seller');
    $response->assertSee('Cart');
});
