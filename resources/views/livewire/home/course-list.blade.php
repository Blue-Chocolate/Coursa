<div class="max-w-6xl mx-auto px-6 py-16">

    {{-- Header --}}
    <div class="mb-12">
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-2">All Courses</p>
        <h1 class="text-4xl font-bold">Start Learning Today</h1>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-10">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Search courses..."
            class="flex-1 px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
        />
        <select
            wire:model.live="levelId"
            class="px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
        >
            <option value="">All Levels</option>
            @foreach($levels as $level)
                <option value="{{ $level->id }}">{{ $level->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Loading state --}}
    <div wire:loading class="text-center py-8 text-zinc-400 text-sm">Loading courses...</div>

    {{-- Grid --}}
    <div wire:loading.remove class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <a href="/courses/{{ $course->slug }}"
               class="group block rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:shadow-lg hover:border-amber-300 dark:hover:border-amber-600 transition-all duration-200">

                {{-- Thumbnail --}}
                <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                    @if($course->image)
                        <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🎓</div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-md bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400">
                            {{ $course->level->name }}
                        </span>
                        <span class="text-xs text-zinc-400">{{ $course->lessons_count }} lessons</span>

                        @if($course->price == 0)
                            <span class="ml-auto text-xs font-bold text-emerald-500">FREE</span>
                        @else
                            <span class="ml-auto text-xs font-bold text-zinc-700 dark:text-zinc-300">${{ number_format($course->price, 2) }}</span>
                        @endif
                    </div>

                    <h2 class="font-semibold text-base leading-snug group-hover:text-amber-500 transition-colors">
                        {{ $course->title }}
                    </h2>

                    @if($course->description)
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 line-clamp-2">{{ $course->description }}</p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-20 text-zinc-400">
                <p class="text-5xl mb-4">🔍</p>
                <p class="text-lg font-medium">No courses found</p>
                <p class="text-sm mt-1">Try adjusting your search or filters</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $courses->links() }}
    </div>
</div>