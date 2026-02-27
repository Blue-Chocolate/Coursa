<!DOCTYPE html>
<html lang="en" x-data="darkMode()" :class="{ 'dark': isDark }">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Learn Anything')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    {{-- ─────────────────────────────────────────────────────────────
         Flash prevention: apply dark class BEFORE Alpine boots
         so there's no white flash on page load for dark mode users
         ───────────────────────────────────────────────────────────── --}}
<script>
    (function () {
        const stored   = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark   = stored === 'dark' || (!stored && prefersDark);
        if (isDark) document.documentElement.classList.add('dark');
    })();
</script>

    {{-- Alpine.js Component #5: Dark mode toggle
         Defined in <head> so it's available when Alpine boots       --}}
    <script>
    function darkMode() {
        return {
            isDark: document.documentElement.classList.contains('dark'),

            toggle() {
                this.isDark = !this.isDark;
                document.documentElement.classList.toggle('dark', this.isDark);
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            },
        };
    }
</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 font-['Sora'] antialiased transition-colors duration-300">

    <nav class="sticky top-0 z-50 border-b border-zinc-200 dark:border-zinc-800 bg-stone-50/80 dark:bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight">
                <span class="text-amber-500">Learn</span>Forward
            </a>

            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium hover:text-amber-500 transition-colors">Courses</a>

                @auth
                    <a href="{{ route('my.courses') }}" class="text-sm font-medium hover:text-amber-500 transition-colors">My Learning</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium hover:text-amber-500 transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"    class="text-sm font-medium hover:text-amber-500 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">Register</a>
                @endauth

                {{-- ── Dark mode toggle button ───────────────────────────
                     Sun icon shown in dark mode (click → go light)
                     Moon icon shown in light mode (click → go dark)
                     Icons cross-fade via x-transition                    --}}
                <button
                    @click="toggle()"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    class="relative w-9 h-9 rounded-lg border border-zinc-200 dark:border-zinc-700 flex items-center justify-center hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400/50"
                >
                    {{-- Sun (visible in dark mode) --}}
                    <svg
                        x-show="isDark"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                        class="w-4 h-4 text-amber-400 absolute"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <circle cx="12" cy="12" r="5"/>
                        <path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>

                    {{-- Moon (visible in light mode) --}}
                    <svg
                        x-show="!isDark"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                        class="w-4 h-4 text-zinc-500 dark:text-zinc-400 absolute"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <footer class="border-t border-zinc-200 dark:border-zinc-800 py-8 text-center text-sm text-zinc-400">
        © {{ date('Y') }} LearnForward. Built with Laravel + Livewire.
    </footer>

    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    @livewireScripts
</body>
</html>