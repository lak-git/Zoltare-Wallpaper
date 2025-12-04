<?php

use App\Models\Wallpaper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows access to wallpapers API with bearer token', function () {
    $password = 'password';
    $user = User::factory()->create(["password" => bcrypt($password)]);

    $tokenResponse = test()->postJson('/api/token', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $token = $tokenResponse->json('token');

    Wallpaper::factory()->count(3)->create();

    $response = test()->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/wallpapers');

    $response->assertStatus(200)->assertJsonStructure(['data']);
});
