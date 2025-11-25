<x-app-layout>
    <div class="max-w-xl mx-auto px-6 py-16 text-center space-y-6">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold">Payment successful</h1>
        <p class="text-slate-600 dark:text-slate-300">
            Stripe confirmed your payment. You can download your wallpaper from the gallery immediately.
        </p>
        <a href="{{ route('gallery') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-3 text-white font-semibold hover:bg-indigo-500" wire:navigate>
            Return to gallery
        </a>
    </div>
</x-app-layout>

