<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $category = Str::of($request->string('category')->lower())->slug('-')->value();

        $query = Wallpaper::active()->orderByDesc('is_featured')->orderByDesc('created_at');

        if ($category !== '' && $category !== 'all') {
            $query->where('category', $category);
        }

        $wallpapers = $query->paginate(12)->withQueryString();

        $userPurchases = collect();

        if ($request->user()) {
            $userPurchases = Purchase::query()
                ->where('user_id', (string) $request->user()->getKey())
                ->pluck('wallpaper_id');
        }

        $categories = collect(Wallpaper::active()->distinct('category'))
            ->filter()
            ->pluck('category')
            ->map(fn ($value) => [
                'slug' => (string) $value,
                'label' => Str::headline(str_replace('-', ' ', (string) $value)),
            ]);

        return view('pages.gallery', [
            'wallpapers' => $wallpapers,
            'categories' => $categories,
            'selectedCategory' => $category === '' ? 'all' : (string) $category,
            'userPurchases' => $userPurchases,
        ]);
    }
}

