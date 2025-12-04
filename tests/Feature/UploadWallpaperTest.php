<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('requires authentication to view upload page', function () {
    test()->get(route('upload.create'))->assertRedirect(route('login'));
});

it('allows authenticated users to upload free wallpapers', function () {
    Storage::fake('wallpapers');

    $user = User::factory()->create();

        $response = test()->actingAs($user)->post(route('upload.store'), [
            'title' => 'Aurora',
            'category' => 'nature',
            // Use create() with a jpeg mime so the test doesn't require the GD extension
            'image' => UploadedFile::fake()->create('aurora.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('gallery'));

    $this->assertNotEmpty(
        Storage::disk('wallpapers')->allFiles('user-uploads/'.$user->getKey())
    );
});

