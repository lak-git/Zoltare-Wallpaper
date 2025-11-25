<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Models\Wallpaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function create(): View
    {
        return view('pages.upload');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:50'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:51200'],
        ]);

        try {
            $path = $request->file('image')->store(
                'user-uploads/'.$request->user()->getKey(),
                'wallpapers'
            );

            Wallpaper::create([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'price' => 0,
                'image_path' => $path,
                'uploaded_by' => (string) $request->user()->getKey(),
                'is_featured' => false,
            ]);
        } catch (\Throwable $exception) {
            ErrorLog::create([
                'message' => $exception->getMessage(),
                'context' => ['user_id' => $request->user()->getKey()],
                'level' => 'error',
            ]);

            return back()
                ->withInput()
                ->withErrors(['image' => 'Upload failed, please try again.']);
        }

        return redirect()
            ->route('gallery')
            ->with('status', 'Wallpaper uploaded! We will review it shortly.');
    }
}

