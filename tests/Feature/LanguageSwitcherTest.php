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
