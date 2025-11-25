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

    $response = $this->actingAs($user)->post(route('upload.store'), [
        'title' => 'Aurora',
        'category' => 'nature',
        'image' => UploadedFile::fake()->image('aurora.jpg'),
    ]);

    $response->assertRedirect(route('gallery'));

    $this->assertNotEmpty(
        Storage::disk('wallpapers')->allFiles('user-uploads/'.$user->getKey())
    );
});

