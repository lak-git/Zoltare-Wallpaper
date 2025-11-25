<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
        <div class="min-h-screen flex flex-col">
            <x-site-nav variant="transparent" />

            <div class="flex-1 flex items-center justify-center px-6 py-12">
                <div class="w-full max-w-md rounded-2xl bg-white/90 dark:bg-slate-900/90 shadow-xl border border-slate-100 dark:border-slate-800 backdrop-blur">
                    <div class="px-6 py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <x-site-footer />
        </div>
    </body>
</html>
