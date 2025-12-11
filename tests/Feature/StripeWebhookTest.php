<?php

use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the checkout form for paid wallpapers', function () {
    $user = User::factory()->create();
    $wallpaper = Wallpaper::factory()->create(['price' => 7.25]);

    test()->actingAs($user)
        ->get(route('checkout.show', $wallpaper))
        ->assertOk()
        ->assertSee('Complete your purchase');
});

