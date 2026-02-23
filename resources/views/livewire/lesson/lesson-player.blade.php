{{--
    Alpine.js features on this page:
    #2 — Confirmation modal before marking complete
    #3 — Progress bar animation
    #4 — Plyr initialized via x-init / x-ref
--}}
<div
    class="max-w-5xl mx-auto px-6 py-10"
    x-data="lessonPlayer(@js($isCompleted), @js($watchSeconds), '{{ $lesson->video_url }}')"
    x-init="initPlayer()"
    @lesson-completed.window="onCourseCompleted($event.detail.percentage)"
>

    {{-- Breadcrumb --}}
    <nav class="text-sm text-zinc-400 mb-6 flex items-center gap-2">
        <a href="/courses/{{ $course->slug }}" class="hover:text-amber-500 transition-colors">{{ $course->title }}</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">{{ $lesson->title }}</span>
    </nav>

    {{-- ── Video Player (Plyr — Alpine Feature #4) ─────────────────────── --}}
    <div class="rounded-2xl overflow-hidden bg-black mb-6 aspect-video">
        <div x-ref="player">
            <div
                data-plyr-provider="{{ str_contains($lesson->video_url, 'youtube') ? 'youtube' : 'vimeo' }}"
                data-plyr-embed-id="{{ $lesson->video_url }}"
            ></div>
        </div>
    </div>

    {{-- Lesson Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
            @if($lesson->duration_seconds)
                <p class="text-sm text-zinc-400 mt-1">{{ gmdate('G:i:s', $lesson->duration_seconds) }}</p>
            @endif
        </div>

        {{-- Mark Complete + Modal (Alpine Feature #2) --}}
        @if($isEnrolled)
            <div x-data="{ showModal: false }">

                {{-- Trigger Button --}}
                <button
                    @click="showModal = true"
                    x-bind:disabled="completed"
                    class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                    :class="completed
                        ? 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 cursor-default'
                        : 'bg-amber-500 hover:bg-amber-400 text-white'"
                >
                    <span x-show="!completed">✓ Mark as Complete</span>
                    <span x-show="completed">✅ Completed</span>
                </button>

                {{-- ── Confirmation Modal ────────────────────────────────── --}}
                <div
                    x-show="showModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                    @keydown.escape.window="showModal = false"
                    @click.self="showModal = false"
                >
                    <div
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4"
                        @click.stop
                    >
                        <div class="text-4xl text-center mb-4">🎯</div>
                        <h3 class="text-lg font-bold text-center mb-2">Mark Lesson Complete?</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center mb-6">
                            This will update your progress. Make sure you've finished watching before confirming.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showModal = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
                            >
                                Not Yet
                            </button>
                            <button
                                @click="showModal = false; $wire.markComplete()"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors"
                            >
                                Yes, Complete!
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>

    {{-- ── Course Progress Bar (Alpine Feature #3) ─────────────────────── --}}
    @if($isEnrolled)
        <div class="mb-8 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-zinc-500">Course Progress</span>
                <span class="font-bold text-amber-500" x-text="courseProgress + '%'"></span>
            </div>
            <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                <div
                    class="h-full bg-amber-400 rounded-full transition-all duration-700 ease-out"
                    :style="'width: ' + courseProgress + '%'"
                ></div>
            </div>
        </div>
    @endif

    {{-- ── Next / Previous Navigation ──────────────────────────────────── --}}
    <div class="flex justify-between gap-4">
        @if($previous)
            <a href="/courses/{{ $course->slug }}/lessons/{{ $previous->id }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium hover:border-amber-300 hover:text-amber-500 transition-all">
                ← {{ $previous->title }}
            </a>
        @else
            <div></div>
        @endif

        @if($next)
            <a href="/courses/{{ $course->slug }}/lessons/{{ $next->id }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold hover:opacity-90 transition-opacity">
                {{ $next->title }} →
            </a>
        @else
            <a href="/courses/{{ $course->slug }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">
                Back to Course →
            </a>
        @endif
    </div>

</div>