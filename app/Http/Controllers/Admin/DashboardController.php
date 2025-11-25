<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.dashboard', [
            'totalUsers' => User::count(),
            'totalWallpapers' => Wallpaper::count(),
            'totalPurchases' => Purchase::count(),
            'latestErrors' => ErrorLog::query()->orderByDesc('created_at')->limit(5)->get(),
            'recentWallpapers' => Wallpaper::query()->latest()->limit(5)->get(),
        ]);
    }
}

