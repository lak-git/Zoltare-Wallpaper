@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Gallery')
<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-12 space-y-10">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-indigo-500 font-semibold">Explore</p>
                <h1 class="text-3xl font-bold">Wallpaper Gallery</h1>
                <p class="text-slate-600 dark:text-slate-300 mt-2">
                    Browse community uploads, filter by category, and unlock premium drops with a quick checkout.
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
                <x-wallpaper-card :wallpaper="$wallpaper" :user-purchases="$userPurchases" :highlight-id="request('highlight')" />
                @endforeach
            </div>
        @endif

        <div>
            {{ $wallpapers->links() }}
        </div>
    </div>
</x-app-layout>

