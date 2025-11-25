<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12 space-y-8">
        <div>
            <p class="text-sm text-indigo-500 font-semibold">Share your art</p>
            <h1 class="text-3xl font-bold">Upload a wallpaper</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-300">
                Accepted formats: JPG or PNG up to 50MB. Paid wallpapers can be configured by admins only; community uploads stay free.
            </p>
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

        <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800 rounded-3xl p-8 shadow">
            @csrf

            <div>
                <label for="title" class="text-sm font-semibold text-slate-600 dark:text-slate-300">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required
                       class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-indigo-500">
            </div>

            <div>
                <label for="category" class="text-sm font-semibold text-slate-600 dark:text-slate-300">Category</label>
                <select id="category" name="category" required class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-indigo-500">
                    <option value="">Choose a category</option>
                    <option value="sci-fi" @selected(old('category') === 'sci-fi')>Sci-Fi</option>
                    <option value="nature" @selected(old('category') === 'nature')>Nature</option>
                    <option value="architecture" @selected(old('category') === 'architecture')>Architecture</option>
                    <option value="sky" @selected(old('category') === 'sky')>Sky</option>
                    <option value="space" @selected(old('category') === 'space')>Space</option>
                </select>
            </div>

            <div>
                <label for="image" class="text-sm font-semibold text-slate-600 dark:text-slate-300">Wallpaper file</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png" required
                       class="mt-2 w-full rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-transparent px-4 py-6 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <button class="w-full rounded-full bg-indigo-600 px-6 py-3 text-white font-semibold text-lg hover:bg-indigo-500">
                Upload wallpaper
            </button>
        </form>
    </div>
</x-app-layout>

