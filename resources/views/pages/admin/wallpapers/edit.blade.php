@section('title', 'Admin - Wallpapers')
<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-10 space-y-8">
        <div>
            <h1 class="text-3xl font-bold">Edit wallpaper</h1>
            <p class="text-slate-500">Update details for {{ $wallpaper->title }}.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50/70 px-4 py-3 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-200">
                <ul class="list-disc ms-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.wallpapers.update', $wallpaper) }}" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800 rounded-3xl p-8 shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-semibold text-slate-600 dark:text-slate-300">Title</label>
                <input type="text" name="title" value="{{ old('title', $wallpaper->title) }}" required class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-indigo-500">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-600 dark:text-slate-300">Category</label>
                <select name="category" required class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-indigo-500">
                    @foreach (['sci-fi' => 'Sci-Fi', 'nature' => 'Nature', 'architecture' => 'Architecture', 'sky' => 'Sky', 'space' => 'Space'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('category', $wallpaper->category) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-600 dark:text-slate-300">Price (USD)</label>
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $wallpaper->price) }}" required class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-indigo-500">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-600 dark:text-slate-300">Replace image</label>
                <input type="file" name="image" accept="image/jpeg,image/png" class="mt-2 w-full rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 px-4 py-6">
                <p class="text-xs text-slate-500 mt-2">Leave empty to keep the current file.</p>
            </div>

            <div class="flex items-center gap-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 dark:border-slate-600" @checked(old('is_featured', $wallpaper->is_featured))>
                    <span class="text-sm text-slate-600 dark:text-slate-300">Featured</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 dark:border-slate-600" @checked(old('is_active', $wallpaper->is_active))>
                    <span class="text-sm text-slate-600 dark:text-slate-300">Active</span>
                </label>
            </div>

            <button class="w-full rounded-full bg-indigo-600 px-6 py-3 text-white font-semibold text-lg hover:bg-indigo-500">
                Save changes
            </button>
        </form>
    </div>
</x-app-layout>

