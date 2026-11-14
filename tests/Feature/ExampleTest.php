<?php

test('returns a successful response', function () {
    $response = $this->get('/health');

    $response->assertOk();
});

test('welcome page renders successfully with starter kit landing content', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Laravertex');
    $response->assertSee('Web y APK Nativo');
    $response->assertSee('Capacitor 7');
    $response->assertSee('https://github.com/devcabeza/starter-kit');
    $response->assertSee('Ver en GitHub');
});
