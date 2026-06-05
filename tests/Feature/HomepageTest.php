<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('renders the homepage successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('public.home');
});
