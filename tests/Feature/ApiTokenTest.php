<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can obtain a personal access token with valid credentials', function () {
    $password = 'password';
    $user = User::factory()->create(["password" => bcrypt($password)]);

    $response = $this->postJson('/api/token', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)->assertJsonStructure(['token', 'token_type']);
});
