<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Zoltare Admin',
            'email' => 'admin@zoltare.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Wallpaper Fan',
            'email' => 'user@zoltare.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $wallpapers = [
            ['title' => 'Nebula Bloom', 'category' => 'Space', 'price' => 0],
            ['title' => 'Glass City Lights', 'category' => 'Architecture', 'price' => 6.00],
            ['title' => 'Azure Valley', 'category' => 'Nature', 'price' => 3.50],
            ['title' => 'Cyber Skyline', 'category' => 'Sci-Fi', 'price' => 8.50],
            ['title' => 'Crimson Horizon', 'category' => 'Sky', 'price' => 0],
        ];

        foreach ($wallpapers as $index => $wallpaper) {
            Wallpaper::create([
                'title' => $wallpaper['title'],
                'category' => $wallpaper['category'],
                'price' => $wallpaper['price'],
                'image_path' => $this->seedWallpaperFile("demo-wallpaper-{$index}.png"),
                'uploaded_by' => $admin->_id ?? (string) $admin->getKey(),
                'is_featured' => $index < 3,
            ]);
        }
    }

    private function seedWallpaperFile(string $filename): string
    {
        $path = 'seeded/'.$filename;

        if (! Storage::disk('wallpapers')->exists($path)) {
            $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z/C/HwAFgwJ/ludIIQAAAABJRU5ErkJggg==');
            Storage::disk('wallpapers')->put($path, $pixel);
        }

        return $path;
    }
}
