@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-12 space-y-10">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-indigo-500 font-semibold">Explore</p>
                <h1 class="text-3xl font-bold">Wallpaper Gallery</h1>
                <p class="text-slate-600 dark:text-slate-300 mt-2">
                    Browse community uploads, filter by category, and unlock premium drops via Stripe Checkout.
                </p>
            </div>
            <form method="GET" class="flex items-center gap-3">
                <label for="category" class="text-sm font-medium text-slate-500">Category</label>
                <select id="category" name="category" class="rounded-full border-slate-300 dark:border-slate-700 bg-transparent px-4 py-2"
                        onchange="this.form.submit()">
                    <option value="all" @selected($selectedCategory === 'all')>All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['slug'] }}" @selected($selectedCategory === $category['slug'])>
                            {{ $category['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-500/40 dark:bg-indigo-500/10 dark:text-indigo-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($wallpapers->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 px-6 py-12 text-center text-slate-500">
                No wallpapers found for this category.
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($wallpapers as $wallpaper)
                <article class="rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur shadow p-4 flex flex-col gap-4 @if(request('highlight') === (string) $wallpaper->getKey()) ring-2 ring-indigo-500 @endif">
                    <div class="h-48 rounded-xl bg-gradient-to-br from-slate-800 to-indigo-700 flex items-center justify-center text-white text-2xl font-semibold">
                        <img src="{{ route('wallpapers.image', $wallpaper) }}" alt="Wallpaper: {{ $wallpaper->title }}">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $wallpaper->categoryLabel() }}</p>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $wallpaper->title }}</h3>
                        <p class="text-sm text-slate-500">Uploaded {{ $wallpaper->created_at?->diffForHumans() ?? 'recently' }}</p>
                    </div>
                    <div class="mt-auto flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-indigo-600">
                                {{ $wallpaper->isFree() ? 'Free' : '$'.number_format($wallpaper->price, 2) }}
                            </span>
                            @if (! $wallpaper->isFree() && auth()->check() && $userPurchases->contains((string) $wallpaper->getKey()))
                                <span class="text-xs font-semibold text-emerald-500">Owned</span>
                            @endif
                        </div>

                        @if ($wallpaper->isFree())
                            <a href="{{ route('wallpapers.download', $wallpaper) }}" class="inline-flex justify-center rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">
                                Download
                            </a>
                        @elseif (auth()->check() && ($userPurchases->contains((string) $wallpaper->getKey()) || auth()->user()->isAdmin()))
                            <a href="{{ route('wallpapers.download', $wallpaper) }}" class="inline-flex justify-center rounded-full bg-emerald-600 px-4 py-2 text-white font-semibold hover:bg-emerald-500">
                                Download purchase
                            </a>
                        @elseif (auth()->check())
                            <form method="POST" action="{{ route('checkout.start', $wallpaper) }}">
                                @csrf
                                <button class="w-full rounded-full border border-indigo-200 px-4 py-2 font-semibold text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10">
                                    Purchase via Stripe
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 hover:border-indigo-300 dark:text-slate-200" wire:navigate>
                                Login to buy
                            </a>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
        @endif

        <div>
            {{ $wallpapers->links() }}
        </div>
    </div>
</x-app-layout>

