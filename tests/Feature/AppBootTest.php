<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('boots the application successfully', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
