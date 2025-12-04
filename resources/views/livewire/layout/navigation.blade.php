<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component as VoltComponent;

new class extends VoltComponent
{
    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="bg-white dark:bg-slate-900 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400" wire:navigate>
                    Zoltare
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600 dark:text-slate-300">
                    <a href="{{ url('/') }}" wire:navigate class="hover:text-indigo-500 dark:hover:text-indigo-300">Home</a>
                    <a href="{{ route('gallery') }}" wire:navigate class="hover:text-indigo-500 dark:hover:text-indigo-300">Gallery</a>
                    <a href="{{ route('upload.create') }}" wire:navigate class="hover:text-indigo-500 dark:hover:text-indigo-300">Upload</a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-semibold text-slate-600 dark:text-slate-200 hover:text-indigo-500 dark:hover:text-indigo-300">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>