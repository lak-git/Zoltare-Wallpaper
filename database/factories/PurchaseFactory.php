<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'wallpaper_id' => Wallpaper::factory(),
            'status' => 'paid',
        ];
    }
}

