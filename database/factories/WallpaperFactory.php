<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Wallpaper>
 */
class WallpaperFactory extends Factory
{
    protected $model = Wallpaper::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'category' => fake()->randomElement(['Sci-Fi', 'Nature', 'Architecture', 'Sky', 'Space']),
            'price' => fake()->randomElement([0, 0, 0, fake()->randomFloat(2, 1, 15)]),
            'image_path' => 'seeded/sample-'.fake()->uuid().'.png',
            'uploaded_by' => User::factory(),
            'is_featured' => fake()->boolean(20),
            'is_active' => true,
        ];
    }
}

