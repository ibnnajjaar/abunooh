<?php

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
