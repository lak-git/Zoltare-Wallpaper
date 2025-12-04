<?php

use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('persists purchases from stripe webhook payloads', function () {
    config(['services.stripe.webhook_secret' => 'whsec_test']);

    $payload = [
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_123',
                'metadata' => [
                    'user_id' => 'user123',
                    'wallpaper_id' => 'wallpaper456',
                ],
            ],
        ],
    ];

    test()->withHeader('Stripe-Signature', 'test')
        ->postJson('/api/stripe/webhook', $payload)
        ->assertOk();

    expect(Purchase::where('user_id', 'user123')->where('wallpaper_id', 'wallpaper456')->where('status', 'paid')->exists())
        ->toBeTrue();
});

