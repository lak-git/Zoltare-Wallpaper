<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $featured = Wallpaper::active()
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'wallpapers' => Wallpaper::active()->count(),
            'artists' => User::count(),
            'purchases' => Purchase::count(),
        ];

        return view('pages.landing', [
            'featured' => $featured,
            'stats' => $stats,
        ]);
    }
}

