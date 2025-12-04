<x-app-layout>
<div class="bg-slate-50 dark:bg-slate-950">
    <section class="max-w-6xl mx-auto px-6 py-16 grid gap-10 lg:grid-cols-2 items-center">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-indigo-500">Digital Wallpapers</p>
            <h1 class="mt-4 text-4xl md:text-5xl font-bold text-slate-900 dark:text-white">
                Discover & collect premium wallpapers crafted by the Zoltare community.
            </h1>
            <p class="mt-6 text-lg text-slate-600 dark:text-slate-300">
                High-resolution artwork, curated daily. Support independent artists, unlock exclusive sci-fi, nature,
                and architectural scenes, and keep your space-inspired setup fresh.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-6 py-3 text-white font-semibold shadow-lg shadow-indigo-600/30 hover:bg-indigo-500" wire:navigate>
                    Browse gallery
                </a>
                <a href="#featured" class="inline-flex items-center gap-2 rounded-full border border-slate-300 dark:border-slate-700 px-6 py-3 text-slate-700 dark:text-slate-200 font-semibold hover:border-indigo-500 hover:text-indigo-500">
                    See featured drops
                </a>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($featured as $wallpaper)
                <div class="rounded-3xl overflow-hidden bg-white/80 dark:bg-slate-900/70 border border-slate-100 dark:border-slate-800 shadow backdrop-blur">
                    <img src="{{ route('wallpapers.image', $wallpaper) }}" alt="Wallpaper: {{ $wallpaper->title }}">
                    <div class="p-4">
                        <p class="text-sm uppercase tracking-wide text-slate-500">{{ $wallpaper->categoryLabel() }}</p>
                        <p class="mt-2 font-semibold text-slate-900 dark:text-white">
                            {{ $wallpaper->isFree() ? 'Free download' : '$'.number_format($wallpaper->price, 2) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="featured" class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm text-indigo-500 font-semibold">Featured gallery</p>
                    <h2 class="mt-2 text-3xl font-bold">Curated drops from the community</h2>
                </div>
                <a href="{{ route('gallery') }}" class="text-indigo-600 font-semibold hover:text-indigo-400" wire:navigate>
                    View full gallery →
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $wallpaper)
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 p-5 flex flex-col gap-4">
                        <img src="{{ route('wallpapers.image', $wallpaper) }}" alt="Wallpaper: {{ $wallpaper->title }}" class="w-full h-48 object-cover rounded-xl">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-slate-500">{{ $wallpaper->categoryLabel() }}</p>
                            <h3 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $wallpaper->title }}</h3>
                            <p class="mt-2 text-sm text-slate-500">Uploaded {{ $wallpaper->created_at?->diffForHumans() ?? 'recently' }}</p>
                        </div>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="font-semibold text-indigo-600">
                                {{ $wallpaper->isFree() ? 'Free' : '$'.number_format($wallpaper->price, 2) }}
                            </span>
                            <a href="{{ route('gallery', ['highlight' => $wallpaper->id]) }}" class="text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-indigo-500" wire:navigate>
                                View wallpaper
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
</x-app-layout>

