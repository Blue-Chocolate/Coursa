
<div class="min-h-screen flex font-['Sora'] antialiased">

    {{-- Grain texture --}}
    <div class="pointer-events-none fixed inset-0 z-0 opacity-[0.03] dark:opacity-[0.06]"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E&quot;); background-size: 200px;">
    </div>

    {{-- Dark mode toggle — toggle() & isDark live on <html> x-data scope --}}
    <button @click="toggle()"
            class="fixed top-5 right-5 z-50 w-9 h-9 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center justify-center hover:border-amber-400 transition-colors shadow-sm">
        <span x-show="!isDark" class="text-sm">☀️</span>
        <span x-show="isDark"  class="text-sm">🌙</span>
    </button>

    {{-- ── Left Panel ─────────────────────────────────────────────────────── --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-zinc-900">
        <div class="absolute inset-0">
            <div class="absolute top-[-10%] right-[-10%] w-[450px] h-[450px] rounded-full bg-amber-500/15 blur-[100px]"></div>
            <div class="absolute bottom-[10%] left-[-5%] w-[350px] h-[350px] rounded-full bg-teal-500/10 blur-[90px]"></div>
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-between p-14 w-full">
            <a href="/" class="text-xl font-bold text-white">
                <span class="text-amber-400">Learn</span>Forward
            </a>

            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-zinc-300 text-xs font-semibold tracking-wider uppercase">Welcome back</span>
                </div>
                <h2 class="text-5xl font-bold text-white leading-[1.1] mb-6">
                    Continue<br />where you<br /><span class="text-amber-400">left off.</span>
                </h2>
                <p class="text-zinc-400 text-base leading-relaxed max-w-xs">
                    Your courses, progress, and achievements are waiting for you.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4">
                @foreach([['2.4k+', 'Learners'], ['48', 'Courses'], ['94%', 'Completion']] as [$num, $label])
                    <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                        <p class="text-2xl font-bold text-white">{{ $num }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Right Panel — Form ──────────────────────────────────────────────── --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 bg-stone-100 dark:bg-zinc-950 relative z-10">
        <div class="w-full max-w-md"
             x-data="{
                 mounted: false,
                 attempts: @entangle('attempts'),
                 shaking: false,
                 init() {
                     setTimeout(() => this.mounted = true, 50);
                     this.$watch('attempts', (val) => {
                         if (val > 0) {
                             this.shaking = true;
                             setTimeout(() => this.shaking = false, 600);
                         }
                     });
                 }
             }">

            <div :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-500">

                <div class="mb-8">
                    <a href="/" class="lg:hidden inline-block text-xl font-bold mb-8 text-zinc-900 dark:text-white">
                        <span class="text-amber-500">Learn</span>Forward
                    </a>
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Welcome back</h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-2 text-sm">
                        New here?
                        <a href="/register" class="text-amber-500 hover:text-amber-400 font-semibold transition-colors">Create an account →</a>
                    </p>
                </div>

                <form wire:submit="login"
                      :class="shaking ? 'animate-[shake_0.5s_ease-in-out]' : ''"
                      class="space-y-5">

                    @if($errors->any())
                        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800">
                            <span class="text-red-500 text-lg flex-shrink-0">⚠</span>
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Email Address</label>
                        <input wire:model="email" type="email" placeholder="jane@example.com" autocomplete="email" autofocus
                               class="w-full px-4 py-3 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('email') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Password</label>
                            <a href="/forgot-password" class="text-xs text-amber-500 hover:text-amber-400 transition-colors font-medium">Forgot password?</a>
                        </div>
                        <div x-data="{ show: false }" class="relative">
                            <input wire:model="password" :type="show ? 'text' : 'password'" placeholder="Your password" autocomplete="current-password"
                                   class="w-full px-4 py-3 pr-11 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                          @error('email') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors p-1">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show"  class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me --}}
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input wire:model="remember" type="checkbox"
                               class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 text-amber-500 focus:ring-amber-400 cursor-pointer" />
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-200 transition-colors">
                            Keep me signed in
                        </span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" wire:loading.attr="disabled"
                            class="relative w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white font-semibold text-sm transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 hover:-translate-y-0.5">
                        <span wire:loading.remove>Sign In</span>
                        <span wire:loading class="flex items-center justify-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Signing in...
                        </span>
                    </button>

                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15%       { transform: translateX(-6px); }
            30%       { transform: translateX(6px); }
            45%       { transform: translateX(-4px); }
            60%       { transform: translateX(4px); }
            75%       { transform: translateX(-2px); }
            90%       { transform: translateX(2px); }
        }
    </style>

</div>