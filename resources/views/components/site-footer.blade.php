<footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
    <div class="max-w-6xl mx-auto px-4 py-8 text-sm text-slate-500 dark:text-slate-400 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <p>&copy; {{ now()->year }} Zoltare Wallpapers. All rights reserved.</p>
        <div class="flex items-center gap-4">
            <a href="{{ route('gallery') }}" class="hover:text-indigo-500" wire:navigate>Gallery</a>
            <a href="{{ route('upload.create') }}" class="hover:text-indigo-500" wire:navigate>Upload</a>
        </div>
    </div>
</footer>

