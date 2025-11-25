<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminWallpaperRequest;
use App\Http\Requests\AdminWallpaperUpdateRequest;
use App\Models\ErrorLog;
use App\Models\Purchase;
use App\Models\Wallpaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WallpaperController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.wallpapers.index', [
            'wallpapers' => Wallpaper::query()->orderByDesc('created_at')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.wallpapers.create');
    }

    public function store(AdminWallpaperRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $path = $request->file('image')->store('admin/'.$request->user()->getKey(), 'wallpapers');

            Wallpaper::create([
                'title' => $data['title'],
                'category' => $data['category'],
                'price' => $data['price'],
                'image_path' => $path,
                'uploaded_by' => (string) $request->user()->getKey(),
                'is_featured' => (bool) ($data['is_featured'] ?? false),
                'is_active' => (bool) ($data['is_active'] ?? true),
            ]);
        } catch (\Throwable $exception) {
            ErrorLog::create([
                'message' => $exception->getMessage(),
                'context' => ['action' => 'admin_store'],
                'level' => 'error',
            ]);

            return back()->withInput()->withErrors(['image' => 'Failed to store wallpaper.']);
        }

        return redirect()->route('admin.wallpapers.index')->with('status', 'Wallpaper created.');
    }

    public function edit(Wallpaper $wallpaper): View
    {
        return view('pages.admin.wallpapers.edit', compact('wallpaper'));
    }

    public function update(AdminWallpaperUpdateRequest $request, Wallpaper $wallpaper): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('wallpapers')->delete($wallpaper->image_path);
            $data['image_path'] = $request->file('image')->store('admin/'.$request->user()->getKey(), 'wallpapers');
        }

        $wallpaper->update([
            'title' => $data['title'],
            'category' => $data['category'],
            'price' => $data['price'],
            'image_path' => $data['image_path'] ?? $wallpaper->image_path,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()->route('admin.wallpapers.index')->with('status', 'Wallpaper updated.');
    }

    public function destroy(Wallpaper $wallpaper): RedirectResponse
    {
        Storage::disk('wallpapers')->delete($wallpaper->image_path);

        Purchase::query()->where('wallpaper_id', (string) $wallpaper->getKey())->delete();

        $wallpaper->delete();

        return redirect()->route('admin.wallpapers.index')->with('status', 'Wallpaper removed.');
    }
}

