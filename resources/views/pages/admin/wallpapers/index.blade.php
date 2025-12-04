@section('title', 'Admin - Wallpapers')
<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-10 space-y-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold">Manage wallpapers</h1>
                <p class="text-slate-500">Create, update, or remove catalog items.</p>
            </div>
            <a href="{{ route('admin.wallpapers.create') }}" class="rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold">
                + Add wallpaper
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/80 shadow">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @foreach ($wallpapers as $wallpaper)
                        <tr>
                            <td class="px-6 py-4 font-semibold">{{ $wallpaper->title }}</td>
                            <td class="px-6 py-4">{{ $wallpaper->categoryLabel() }}</td>
                            <td class="px-6 py-4">{{ $wallpaper->isFree() ? 'Free' : '$'.number_format($wallpaper->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $wallpaper->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $wallpaper->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.wallpapers.edit', $wallpaper) }}" class="text-indigo-500 font-semibold me-3" wire:navigate>Edit</a>
                                <form method="POST" action="{{ route('admin.wallpapers.destroy', $wallpaper) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 font-semibold" onclick="return confirm('Delete this wallpaper?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $wallpapers->links() }}
    </div>
</x-app-layout>

