<div class="min-h-screen flex">

    {{-- Grain texture --}}
    <div class="pointer-events-none fixed inset-0 z-0 opacity-[0.03] dark:opacity-[0.06]"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E&quot;); background-size: 200px;"></div>

    {{-- Dark toggle --}}
    <button @click="toggle()"
            class="fixed top-5 right-5 z-50 w-9 h-9 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center justify-center hover:border-amber-400 transition-colors shadow-sm">
        <span x-show="!isDark" class="text-sm">☀️</span>
        <span x-show="isDark"  class="text-sm">🌙</span>
    </button>

    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-zinc-900">
        <div class="absolute inset-0">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-amber-500/20 blur-[120px]"></div>
            <div class="absolute bottom-[-5%] right-[-5%] w-[400px] h-[400px] rounded-full bg-teal-500/10 blur-[100px]"></div>
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-between p-14 w-full">
            <a href="{{ route('home') }}" class="text-xl font-bold text-white">
                <span class="text-amber-400">Learn</span>Forward
            </a>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span class="text-amber-400 text-xs font-semibold tracking-wider uppercase">Join today for free</span>
                </div>
                <h2 class="text-5xl font-bold text-white leading-[1.1] mb-6">
                    Your learning<br />journey<br /><span class="text-amber-400">starts here.</span>
                </h2>
                <p class="text-zinc-400 text-base leading-relaxed max-w-xs">
                    Get instant access to expert-led courses, track your progress, and earn completion certificates.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex -space-x-2">
                    @foreach(['🧑‍💻','👩‍🎓','🧑‍🏫','👨‍💼'] as $emoji)
                        <div class="w-9 h-9 rounded-full bg-zinc-700 border-2 border-zinc-900 flex items-center justify-center text-sm">{{ $emoji }}</div>
                    @endforeach
                </div>
                <p class="text-zinc-400 text-sm">
                    <span class="text-white font-semibold">2,400+</span> learners enrolled this month
                </p>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 bg-stone-100 dark:bg-zinc-950 relative z-10">
        <div class="w-full max-w-md"
             x-data="{ mounted: false }"
             x-init="setTimeout(() => mounted = true, 50)">

            <div :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-500">

                <div class="mb-8">
                    <a href="{{ route('home') }}" class="lg:hidden inline-block text-xl font-bold mb-8 text-zinc-900 dark:text-white">
                        <span class="text-amber-500">Learn</span>Forward
                    </a>
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Create an account</h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-2 text-sm">
                        Already have one?
                        <a href="{{ route('login') }}" class="text-amber-500 hover:text-amber-400 font-semibold transition-colors">Sign in →</a>
                    </p>
                </div>

                <form wire:submit="register" class="space-y-5">

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Full Name</label>
                        <input wire:model="name" type="text" placeholder="Jane Doe" autocomplete="name"
                               class="w-full px-4 py-3 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('name') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Email Address</label>
                        <input wire:model="email" type="email" placeholder="jane@example.com" autocomplete="email"
                               class="w-full px-4 py-3 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                      @error('email') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                   {{-- Password --}}
<div x-data="{ show: false, password: '' }">
    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Password</label>
    <div class="relative">
        <input
            wire:model="password"
            x-model="password"
            :type="show ? 'text' : 'password'"
            placeholder="Min. 8 characters"
            autocomplete="new-password"
            class="w-full px-4 py-3 pr-11 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                   @error('password') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
        <button type="button" @click="show = !show"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors p-1">
            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
        </button>
    </div>

    {{-- Strength bar — reads from Alpine's own 'password' variable, not $wire --}}
    <div class="mt-2 flex gap-1">
        <template x-for="i in 4" :key="i">
            <div class="flex-1 h-1 rounded-full transition-all duration-300"
                 :class="{
                     'bg-red-400':     i <= strength && strength === 1,
                     'bg-amber-400':   i <= strength && strength === 2,
                     'bg-yellow-400':  i <= strength && strength === 3,
                     'bg-emerald-400': i <= strength && strength === 4,
                     'bg-zinc-200 dark:bg-zinc-700': i > strength
                 }"
                 x-data="{
                     get strength() {
                         let s = 0;
                         if (password.length >= 8) s++;
                         if (/[A-Z]/.test(password)) s++;
                         if (/[0-9]/.test(password)) s++;
                         if (/[^A-Za-z0-9]/.test(password)) s++;
                         return s;
                     }
                 }">
            </div>
        </template>
    </div>

    @error('password')
        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>
                    {{-- Confirm Password --}}
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input wire:model="password_confirmation" :type="show ? 'text' : 'password'" placeholder="Repeat your password" autocomplete="new-password"
                                   class="w-full px-4 py-3 pr-11 rounded-xl bg-white dark:bg-zinc-900 border text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                                          @error('password_confirmation') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror" />
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors p-1">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show"  class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white font-semibold text-sm transition-all disabled:opacity-60 disabled:cursor-not-allowed shadow-lg shadow-amber-500/20 hover:-translate-y-0.5">
                        <span wire:loading.remove>Create Account</span>
                        <span wire:loading class="flex items-center justify-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Creating account...
                        </span>
                    </button>

                    <p class="text-center text-xs text-zinc-400 dark:text-zinc-500">
                        By registering you agree to our <a href="#" class="underline hover:text-zinc-600 dark:hover:text-zinc-300">Terms of Service</a>
                    </p>

                </form>
            </div>
        </div>
    </div>

</div>