@props(['variant' => 'default'])

@php
    $links = [
        ['href' => route('landing'), 'label' => 'Home', 'active' => request()->routeIs('landing')],
        ['href' => route('gallery'), 'label' => 'Gallery', 'active' => request()->routeIs('gallery')],
    ];

    if (auth()->check()) {
        $links[] = ['href' => route('upload.create'), 'label' => 'Upload', 'active' => request()->routeIs('upload.*')];
    }
@endphp

<nav class="{{ $variant === 'transparent' ? 'bg-white/70 dark:bg-slate-900/70 backdrop-blur shadow-sm' : 'bg-white dark:bg-slate-900 shadow-sm' }}">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('landing') }}" class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400" wire:navigate>
                    Zoltare
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600 dark:text-slate-300">
                    @foreach ($links as $link)
                        <a href="{{ $link['href'] }}"
                           wire:navigate
                           class="{{ $link['active'] ? 'text-indigo-600 dark:text-indigo-400' : 'hover:text-indigo-500 dark:hover:text-indigo-300' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="theme-toggle"
                        class="rounded-full border border-slate-200 dark:border-slate-700 p-2 text-slate-700 dark:text-slate-100"
                        aria-label="Toggle dark mode">
                    <svg id="theme-icon-sun" class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0-1.414 1.414M7.05 16.95l-1.414 1.414M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="theme-icon-moon" class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </button>

                @if (auth()->check())
                    <div class="hidden md:flex items-center gap-2">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-indigo-500 dark:hover:text-indigo-300" wire:navigate>
                                Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm font-semibold text-slate-600 dark:text-slate-200 hover:text-indigo-500 dark:hover:text-indigo-300">
                                Logout
                            </button>
                        </form>
                    </div>
                    <div class="md:hidden">
                        <details class="relative">
                            <summary class="cursor-pointer text-sm font-semibold text-slate-600 dark:text-slate-200">
                                Menu
                            </summary>
                            <div class="absolute right-0 mt-2 w-40 rounded-lg bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 p-2 space-y-2">
                                @foreach ($links as $link)
                                    <a href="{{ $link['href'] }}" class="block text-sm" wire:navigate>{{ $link['label'] }}</a>
                                @endforeach
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block text-sm" wire:navigate>Admin</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="text-sm text-left w-full">Logout</button>
                                </form>
                            </div>
                        </details>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-sm font-semibold">
                        <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-500 dark:hover:text-indigo-300" wire:navigate>Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-indigo-600 px-3 py-1.5 text-white shadow hover:bg-indigo-500" wire:navigate>
                            Create account
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>

