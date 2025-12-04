<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents non-admin users from accessing the dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    test()->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('allows admins to access the dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    test()->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Control center');
});

