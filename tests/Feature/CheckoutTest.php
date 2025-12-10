<?php

use App\Models\Purchase;
use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks a purchase as paid after submitting the dummy checkout form', function () {
    $user = User::factory()->create();
    $wallpaper = Wallpaper::factory()->create(['price' => 5.50]);

    $response = test()->actingAs($user)
        ->post(route('checkout.store', $wallpaper), [
            'cardholder_name' => 'Test User',
            'card_number' => '4242424242424242',
            'postal_code' => '12345',
            'card_expiry' => '12/30',
            'card_pin' => '1234',
        ]);

    $response->assertRedirect(route('checkout.success', ['highlight' => (string) $wallpaper->getKey()]));

    expect(Purchase::where('user_id', (string) $user->getKey())
        ->where('wallpaper_id', (string) $wallpaper->getKey())
        ->where('status', 'paid')
        ->exists())->toBeTrue();
});
