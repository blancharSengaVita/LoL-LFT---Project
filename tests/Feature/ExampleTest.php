<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


it('can register', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
