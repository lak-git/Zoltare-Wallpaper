<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Models\Purchase;
use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function __invoke(Request $request, Wallpaper $wallpaper)
    {
        if ($wallpaper->isFree()) {
            return $this->streamWallpaper($wallpaper);
        }

        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $hasAccess = $user->isAdmin()
            || $wallpaper->uploaded_by === (string) $user->getKey()
            || Purchase::where('user_id', (string) $user->getKey())
                ->where('wallpaper_id', (string) $wallpaper->getKey())
                ->exists();

        abort_unless($hasAccess, Response::HTTP_FORBIDDEN);

        return $this->streamWallpaper($wallpaper);
    }

    protected function streamWallpaper(Wallpaper $wallpaper)
    {
        if (! Storage::disk('wallpapers')->exists($wallpaper->image_path)) {
            ErrorLog::create([
                'message' => 'Wallpaper file missing',
                'context' => ['wallpaper_id' => $wallpaper->getKey(), 'path' => $wallpaper->image_path],
                'level' => 'warning',
            ]);

            abort(Response::HTTP_NOT_FOUND);
        }

        $filename = $wallpaper->slug.'.'.pathinfo($wallpaper->image_path, PATHINFO_EXTENSION);

        return response()->download(
            Storage::disk('wallpapers')->path($wallpaper->image_path),
            $filename
        );
    }

    // Public image endpoint for embedding thumbnails in the gallery
    public function image(Wallpaper $wallpaper)
    {
        if (! Storage::disk('wallpapers')->exists($wallpaper->image_path)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $path = Storage::disk('wallpapers')->path($wallpaper->image_path);

        // response()->file serves the file inline so it can be displayed in an <img>
        return response()->file($path);
    }    
}

