<?php

use App\Models\Wallpaper;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the gallery with wallpapers', function () {
    $wallpaper = Wallpaper::factory()->create([
        'title' => 'Test Nebula',
        'price' => 0,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('Wallpaper Gallery')
        ->assertSee($wallpaper->title);
});

