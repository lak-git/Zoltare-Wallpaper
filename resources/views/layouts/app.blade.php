<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Zoltare | @yield('title', 'Home')</title>

        <!-- Favicons -->
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <meta name="msapplication-TileColor" content="#2b5797">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        <script>
            (() => {
                const THEME_KEY = 'zoltare-theme';
                try {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
                    const storedTheme = localStorage.getItem(THEME_KEY);
                    const theme = storedTheme || (prefersDark.matches ? 'dark' : 'light');
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (error) {
                    console.warn('Theme init failed', error);
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
        <div class="min-h-screen flex flex-col">
            <x-site-nav />

            <main class="flex-1">
                {{ $slot }}
            </main>

            <x-site-footer />
        </div>
    </body>
</html>
