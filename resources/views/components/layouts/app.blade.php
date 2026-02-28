<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Learn Anything')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    {{-- Flash prevention: runs synchronously before anything renders --}}
    <script>
        (function () {
            const stored      = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

{{--
    IMPORTANT: x-data is on <body>, NOT <html>.
    Alpine boots from the Vite bundle which loads at end of <body>.
    By then <html> has already been parsed — Alpine misses it.
    <body> is processed after Alpine boots, so timing is correct.
--}}
<body
    x-data="darkMode"
    :class="{ 'dark': isDark }"
    class="bg-stone-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 font-['Sora'] antialiased transition-colors duration-300"
>
    <nav class="sticky top-0 z-50 border-b border-zinc-200 dark:border-zinc-800 bg-stone-50/80 dark:bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight">
                <span class="text-amber-500">Learn</span>Forward
            </a>

            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium hover:text-amber-500 transition-colors">Courses</a>

                @auth
                    <a href="{{ route('my.courses') }}" class="text-sm font-medium hover:text-amber-500 transition-colors">My Learning</a>

                    {{-- ── Notification Bell ────────────────────────────────── --}}
                    <div x-data="notifications" x-init="init()" class="relative">

                        {{-- Bell button --}}
                        <button
                            @click="toggle()"
                            :title="unreadCount > 0 ? `${unreadCount} unread notifications` : 'Notifications'"
                            class="relative w-9 h-9 rounded-lg border border-zinc-200 dark:border-zinc-700 flex items-center justify-center hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400/50"
                        >
                            <svg class="w-4 h-4 text-zinc-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>

                            {{-- Unread badge --}}
                            <span
                                x-show="unreadCount > 0"
                                x-transition
                                x-text="unreadCount > 9 ? '9+' : unreadCount"
                                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center leading-none"
                            ></span>
                        </button>

                        {{-- Dropdown panel --}}
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute right-0 mt-2 w-80 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden z-50"
                            style="display: none;"
                        >
                            {{-- Panel header --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
                                <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">Notifications</span>
                                <button
                                    x-show="unreadCount > 0"
                                    @click="markAllRead()"
                                    class="text-xs text-amber-500 hover:text-amber-400 font-medium transition-colors"
                                >
                                    Mark all read
                                </button>
                            </div>

                            {{-- Loading state --}}
                            <div x-show="loading" class="flex items-center justify-center py-8">
                                <svg class="animate-spin w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                            </div>

                            {{-- Empty state --}}
                            <div
                                x-show="!loading && items.length === 0"
                                class="flex flex-col items-center justify-center py-10 text-zinc-400 gap-2"
                                style="display: none;"
                            >
                                <svg class="w-8 h-8 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm">You're all caught up!</p>
                            </div>

                            {{-- Notification list --}}
                            <ul
                                x-show="!loading && items.length > 0"
                                class="max-h-80 overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800"
                                style="display: none;"
                            >
                                <template x-for="n in items" :key="n.id">
                                    <li>
                                        <a
                                            :href="n.data.url"
                                            @click="n.read_at || markRead(n.id)"
                                            class="flex gap-3 px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition-colors"
                                            :class="{ 'bg-amber-50 dark:bg-amber-500/5': !n.read_at }"
                                        >
                                            {{-- Unread dot --}}
                                            <span class="mt-1.5 flex-shrink-0">
                                                <span
                                                    class="block w-2 h-2 rounded-full transition-colors"
                                                    :class="n.read_at ? 'bg-zinc-200 dark:bg-zinc-700' : 'bg-amber-500'"
                                                ></span>
                                            </span>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-[11px] font-medium text-amber-500 uppercase tracking-wide truncate" x-text="n.data.course_title"></p>
                                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 truncate" x-text="'New lesson: ' + n.data.lesson_title"></p>
                                                <p class="text-xs text-zinc-400 mt-0.5" x-text="timeAgo(n.created_at)"></p>
                                            </div>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    {{-- ── End Notification Bell ────────────────────────────── --}}

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium hover:text-amber-500 transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"    class="text-sm font-medium hover:text-amber-500 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">Register</a>
                @endauth

                {{-- Dark mode toggle --}}
                <button
                    @click="toggle()"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    class="relative w-9 h-9 rounded-lg border border-zinc-200 dark:border-zinc-700 flex items-center justify-center hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400/50"
                >
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