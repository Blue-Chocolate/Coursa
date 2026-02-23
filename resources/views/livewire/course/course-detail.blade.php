<div class="max-w-5xl mx-auto px-6 py-12">

    {{-- Course Hero --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12">

        {{-- Left: Info --}}
        <div class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs font-semibold px-2 py-1 rounded-md bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400">
                    {{ $course->level->name }}
                </span>
                <span class="text-xs text-zinc-400">{{ $course->lessons->count() }} lessons</span>
            </div>

            <h1 class="text-3xl font-bold leading-tight mb-4">{{ $course->title }}</h1>

            @if($course->description)
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $course->description }}</p>
            @endif

            {{-- Progress bar for enrolled users (Alpine.js Feature #3) --}}
            @if($isEnrolled)
                <div class="mt-6"
                     x-data="{ pct: 0, target: {{ $completionPct }} }"
                     x-init="setTimeout(() => pct = target, 300)">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-zinc-600 dark:text-zinc-400">Your progress</span>
                        <span class="font-bold text-amber-500" x-text="pct + '%'"></span>
                    </div>
                    <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full transition-all duration-700 ease-out"
                             :style="'width: ' + pct + '%'">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Card --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 h-fit">
            @if($course->image)
                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                     class="w-full aspect-video object-cover rounded-xl mb-5" />
            @else
                <div class="w-full aspect-video bg-zinc-100 dark:bg-zinc-800 rounded-xl mb-5 flex items-center justify-center text-5xl">🎓</div>
            @endif

            @if($course->price == 0)
                <p class="text-2xl font-bold text-emerald-500 mb-4">Free</p>
            @else
                <p class="text-2xl font-bold mb-4">${{ number_format($course->price, 2) }}</p>
            @endif

            {{-- Enroll / Continue Button --}}
            <livewire:course.enroll-button :course="$course" :is-enrolled="$isEnrolled" />
        </div>

    </div>

    {{-- ── Lesson List (Alpine.js Accordion — Feature #1) ─────────────────── --}}
    <div x-data="{ open: null }">
        <h2 class="text-xl font-bold mb-5">Course Content</h2>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden divide-y divide-zinc-200 dark:divide-zinc-800">

            @foreach($course->lessons as $index => $lesson)
                @php
                    $isCompleted  = $completedIds->contains($lesson->id);
                    $isFree       = $lesson->is_free_preview;
                    $isAccessible = $isFree || $isEnrolled;
                @endphp

                <div x-data="{}">
                    {{-- Accordion Header --}}
                    <button
                        @click="open === {{ $index }} ? open = null : open = {{ $index }}"
                        class="w-full flex items-center gap-4 px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors text-left"
                    >
                        {{-- Status Icon --}}
                        <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm
                            {{ $isCompleted ? 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' }}">
                            @if($isCompleted)
                                ✓
                            @else
                                {{ $index + 1 }}
                            @endif
                        </span>

                        <span class="flex-1 font-medium text-sm">{{ $lesson->title }}</span>

                        <div class="flex items-center gap-3 text-xs text-zinc-400">
                            @if($isFree)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-semibold">
                                    Preview
                                </span>
                            @elseif(!$isEnrolled)
                                <span>🔒</span>
                            @endif

                            @if($lesson->duration_seconds)
                                <span>{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                            @endif

                            <svg class="w-4 h-4 transition-transform duration-200"
                                 :class="open === {{ $index }} ? 'rotate-180' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- Accordion Body --}}
                    <div
                        x-show="open === {{ $index }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="px-5 pb-4 pl-16 text-sm text-zinc-500 dark:text-zinc-400"
                    >
                        @if($isAccessible)
                            <a href="/courses/{{ $course->slug }}/lessons/{{ $lesson->id }}"
                               class="inline-flex items-center gap-2 text-amber-500 hover:text-amber-400 font-semibold mt-1 transition-colors">
                                ▶ {{ $isCompleted ? 'Rewatch Lesson' : 'Watch Lesson' }}
                            </a>
                        @else
                            <p class="mt-1">Enroll in this course to unlock this lesson.</p>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>

</div>