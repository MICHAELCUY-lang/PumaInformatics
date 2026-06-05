<?php

use App\Models\User;

it('redirects guests to login when accessing admin area', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});
