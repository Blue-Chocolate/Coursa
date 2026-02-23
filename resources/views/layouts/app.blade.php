<!DOCTYPE html>
<html lang="en" x-data="darkMode()" :class="{ 'dark': isDark }">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} — @yield('title', 'Learn Anything')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />

    {{-- Plyr CSS --}}
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    {{-- Tailwind (via Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-stone-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 font-['Sora'] antialiased transition-colors duration-300">

    {{-- ── Navbar ───────────────────────────────────────────────────────── --}}
    <nav class="sticky top-0 z-50 border-b border-zinc-200 dark:border-zinc-800 bg-stone-50/80 dark:bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            <a href="/" class="text-lg font-bold tracking-tight">
                <span class="text-amber-500">Learn</span>Forward
            </a>

            <div class="flex items-center gap-6">
                <a href="/" class="text-sm font-medium hover:text-amber-500 transition-colors">Courses</a>

                @auth
                    <a href="/my/courses" class="text-sm font-medium hover:text-amber-500 transition-colors">My Learning</a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="text-sm font-medium hover:text-amber-500 transition-colors">Logout</button>
                    </form>
                @else
                    <a href="/login"    class="text-sm font-medium hover:text-amber-500 transition-colors">Login</a>
                    <a href="/register" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">
                        Register
                    </a>
                @endauth

                {{-- ── Alpine.js Dark Mode Toggle (Feature #5) ──────────── --}}
                <button
                    @click="toggle()"
                    class="w-9 h-9 rounded-lg border border-zinc-200 dark:border-zinc-700 flex items-center justify-center hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                    :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                >
                    <span x-show="!isDark" class="text-base">☀️</span>
                    <span x-show="isDark"  class="text-base">🌙</span>
                </button>
            </div>

        </div>
    </nav>

    {{-- ── Page Content ─────────────────────────────────────────────────── --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────────────────── --}}
    <footer class="border-t border-zinc-200 dark:border-zinc-800 py-8 text-center text-sm text-zinc-400">
        © {{ date('Y') }} LearnForward. Built with Laravel + Livewire.
    </footer>

    {{-- Plyr JS --}}
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    @livewireScripts

    {{-- Alpine.js Dark Mode Global State --}}
    <script>
        function darkMode() {
            return {
                isDark: localStorage.getItem('theme') === 'dark' ||
                        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },
            };
        }
    </script>
</body>
</html>