@section('title', 'Admin')
<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-10 space-y-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-indigo-500">Admin</p>
                <h1 class="text-3xl font-bold">Control center</h1>
                <p class="text-slate-600 dark:text-slate-300 mt-2">Manage wallpapers, monitor purchases, and review error logs.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.wallpapers.create') }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">
                    + Add wallpaper
                </a>
                <a href="{{ route('admin.errors.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 dark:border-slate-700 px-4 py-2 font-semibold text-slate-700 dark:text-slate-200">
                    View errors
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 p-6 shadow">
                <p class="text-sm text-slate-500">Users</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 p-6 shadow">
                <p class="text-sm text-slate-500">Wallpapers</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($totalWallpapers) }}</p>
            </div>
            <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 p-6 shadow">
                <p class="text-sm text-slate-500">Purchases</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($totalPurchases) }}</p>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <section class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Recent uploads</h2>
                    <a href="{{ route('admin.wallpapers.index') }}" class="text-sm text-indigo-500">Manage</a>
                </div>
                <ul class="mt-4 space-y-3">
                    @foreach ($recentWallpapers as $wallpaper)
                        <li class="flex items-center justify-between text-sm">
                            <div>
                                <p class="font-semibold">{{ $wallpaper->title }}</p>
                                <p class="text-slate-500">{{ $wallpaper->categoryLabel() }}</p>
                            </div>
                            <span class="text-indigo-600 font-semibold">
                                {{ $wallpaper->isFree() ? 'Free' : '$'.number_format($wallpaper->price, 2) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Latest errors</h2>
                    <a href="{{ route('admin.errors.index') }}" class="text-sm text-indigo-500">Open logs</a>
                </div>
                <ul class="mt-4 space-y-3">
                    @forelse ($latestErrors as $error)
                        <li class="text-sm text-slate-600 dark:text-slate-300">
                            <p class="font-semibold text-red-500">{{ strtoupper($error->level ?? 'error') }}</p>
                            <p class="truncate">{{ $error->message }}</p>
                            <p class="text-xs text-slate-500">{{ $error->created_at?->diffForHumans() }}</p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">All clear.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</x-app-layout>

