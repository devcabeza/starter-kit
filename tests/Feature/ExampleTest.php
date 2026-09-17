<?php

test('returns a successful response', function () {
    $response = $this->get('/health');

    $response->assertOk();
});
