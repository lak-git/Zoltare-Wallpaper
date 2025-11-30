<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WallpaperResource;
use App\Models\Wallpaper;
use Illuminate\Http\Request;

class WallpaperApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 12);

        $wallpapers = Wallpaper::active()->orderBy('created_at', 'desc')->paginate($perPage);

        return WallpaperResource::collection($wallpapers);
    }

    public function show(Wallpaper $wallpaper)
    {
        return new WallpaperResource($wallpaper);
    }
}
