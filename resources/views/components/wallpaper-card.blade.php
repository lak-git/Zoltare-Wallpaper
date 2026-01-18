@props([
    'wallpaper',
    'userPurchases' => null,
    'highlightId' => null,
])

@php
    $userPurchases = $userPurchases ?? collect();
    $isHighlighted = $highlightId !== null && (string) $highlightId === (string) $wallpaper->getKey();
    $owns = ! $wallpaper->isFree() && auth()->check() && $userPurchases->contains((string) $wallpaper->getKey());
    $canDownloadPaid = auth()->check() && ($owns || auth()->user()->isAdmin());
@endphp

<article {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur shadow p-4 flex flex-col gap-4' . ($isHighlighted ? ' ring-2 ring-indigo-500' : '')]) }}>
    <img src="{{ route('wallpapers.image', $wallpaper) }}" alt="Wallpaper: {{ $wallpaper->title }}" class="w-full h-48 object-cover rounded-xl">
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
            @if ($owns)
                <span class="text-xs font-semibold text-emerald-500">Owned</span>
            @endif
        </div>

        @if ($wallpaper->isFree())
            <a href="{{ route('wallpapers.download', $wallpaper) }}" class="inline-flex justify-center rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">
                Download
            </a>
        @elseif ($canDownloadPaid)
            <a href="{{ route('wallpapers.download', $wallpaper) }}" class="inline-flex justify-center rounded-full bg-emerald-600 px-4 py-2 text-white font-semibold hover:bg-emerald-500">
                Download purchase
            </a>
        @elseif (auth()->check())
            <a href="{{ route('checkout.show', $wallpaper) }}" class="inline-flex w-full justify-center rounded-full border border-indigo-200 px-4 py-2 font-semibold text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10">
                Purchase wallpaper
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 hover:border-indigo-300 dark:text-slate-200" wire:navigate>
                Login to buy
            </a>
        @endif
    </div>
</article>
