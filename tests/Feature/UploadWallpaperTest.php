<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('requires authentication to view upload page', function () {
    $this->get(route('upload.create'))->assertRedirect(route('login'));
});

it('allows authenticated users to upload free wallpapers', function () {
    Storage::fake('wallpapers');

    $user = User::factory()->create();

    // UploadedFile::fake()->image requires GD. If GD is not available in the
    // test environment, fall back to creating a fake file with an image
    // extension. This keeps tests portable across CI and Windows dev boxes.
    $image = null;
    if (function_exists('imagecreatetruecolor')) {
        $image = UploadedFile::fake()->image('aurora.jpg');
    } else {
        $image = UploadedFile::fake()->create('aurora.jpg', 150, 'image/jpeg');
    }

    $response = $this->actingAs($user)->post(route('upload.store'), [
        'title' => 'Aurora',
        'category' => 'nature',
        'image' => $image,
    ]);

    $response->assertRedirect(route('gallery'));

    $this->assertNotEmpty(
        Storage::disk('wallpapers')->allFiles('user-uploads/'.$user->getKey())
    );
});

