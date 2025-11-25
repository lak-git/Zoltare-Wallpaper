<x-app-layout>
    <div class="max-w-xl mx-auto px-6 py-16 text-center space-y-6">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold">Payment canceled</h1>
        <p class="text-slate-600 dark:text-slate-300">
            Your Stripe checkout was canceled. Feel free to browse the gallery and try again whenever you’re ready.
        </p>
        <a href="{{ route('gallery') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:border-indigo-400 hover:text-indigo-500" wire:navigate>
            Back to gallery
        </a>
    </div>
</x-app-layout>

