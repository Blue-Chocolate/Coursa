

<div class="max-w-5xl mx-auto px-6 py-10"
     x-data="lessonPlayer(@js($isCompleted), @js($watchSeconds), @js($lesson->video_url))"
     x-init="initPlayer()"
     @lesson-completed.window="onCourseCompleted($event.detail.percentage)">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-zinc-400 mb-6 flex items-center gap-2">
        <a href="{{ route('course.show', $course->slug) }}" class="hover:text-amber-500 transition-colors">
            {{ $course->title }}
        </a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">{{ $lesson->title }}</span>
    </nav>

    {{-- Plyr video player --}}
    <div class="rounded-2xl overflow-hidden bg-black mb-6 aspect-video">
        <div x-ref="player">
            @if(str_contains($lesson->video_url, 'youtube') || str_contains($lesson->video_url, 'youtu.be'))
                <div data-plyr-provider="youtube" data-plyr-embed-id="{{ $lesson->video_url }}"></div>
            @elseif(str_contains($lesson->video_url, 'vimeo'))
                <div data-plyr-provider="vimeo" data-plyr-embed-id="{{ $lesson->video_url }}"></div>
            @else
                <video controls>
                    <source src="{{ $lesson->video_url }}" />
                </video>
            @endif
        </div>
    </div>

    {{-- Lesson header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $lesson->title }}</h1>
            @if($lesson->duration_seconds)
                <p class="text-sm text-zinc-400 mt-1">{{ gmdate('G:i:s', $lesson->duration_seconds) }}</p>
            @endif
        </div>

        {{-- ─────────────────────────────────────────────────────── --}}
        {{-- COMPONENT 2: Confirmation modal                        --}}
        {{-- Uses parent x-data (lessonPlayer) so `completed` and  --}}
        {{-- `courseProgress` stay in sync after marking complete.  --}}
        {{-- ─────────────────────────────────────────────────────── --}}
        @if($isEnrolled)
            <div x-data="{ showModal: false }">

                {{-- Trigger button --}}
                <button
                    @click="!completed && (showModal = true)"
                    :disabled="completed"
                    class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 flex items-center gap-2"
                    :class="completed
                        ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 cursor-default'
                        : 'bg-amber-500 hover:bg-amber-400 text-white shadow-lg shadow-amber-500/25'"
                >
                    {{-- Completed state --}}
                    <template x-if="completed">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Completed
                        </span>
                    </template>

                    {{-- Pending state --}}
                    <template x-if="!completed">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                            Mark as Complete
                        </span>
                    </template>
                </button>

                {{-- Backdrop --}}
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
                    style="display:none"
                >
                    {{-- Modal panel --}}
                    <div
                        x-show="showModal"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4"
                        @click.stop
                        style="display:none"
                    >
                        <div class="text-4xl text-center mb-4">🎯</div>
                        <h3 class="text-lg font-bold text-center mb-2 text-zinc-900 dark:text-zinc-100">
                            Mark Lesson Complete?
                        </h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center mb-6">
                            Make sure you've finished watching before confirming.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showModal = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-zinc-700 dark:text-zinc-300"
                            >
                                Not Yet
                            </button>
                            <button
                                @click="
                                    showModal = false;
                                    $wire.markComplete();
                                    completed = true;
                                "
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

    {{-- ─────────────────────────────────────────────────────────── --}}
    {{-- COMPONENT 3: Progress bar — animates on completion         --}}
    {{-- courseProgress is in lessonPlayer x-data, updated when     --}}
    {{-- @lesson-completed.window fires from Livewire dispatch      --}}
    {{-- ─────────────────────────────────────────────────────────── --}}
    @if($isEnrolled)
        <div class="mb-8 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-zinc-500 dark:text-zinc-400">Course Progress</span>
                <span
                    class="font-bold transition-colors duration-300"
                    :class="courseProgress === 100 ? 'text-emerald-500' : 'text-amber-500'"
                    x-text="courseProgress + '%'"
                ></span>
            </div>

            {{-- Track --}}
            <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                <div
                    class="h-full rounded-full transition-all duration-700 ease-out"
                    :class="courseProgress === 100
                        ? 'bg-gradient-to-r from-emerald-500 to-emerald-400'
                        : 'bg-amber-400'"
                    :style="'width: ' + courseProgress + '%'"
                ></div>
            </div>

            {{-- Celebration message on 100% --}}
            <p
                x-show="courseProgress === 100"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="text-sm text-emerald-500 font-semibold mt-2 text-center"
                style="display:none"
            >
                🎉 You've completed the course!
            </p>
        </div>
    @endif

    {{-- Prev / Next navigation --}}
    <div class="flex justify-between gap-4">
        @if($previous)
            <a href="{{ route('lesson.show', [$course->slug, $previous->id]) }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium hover:border-amber-300 hover:text-amber-500 transition-all text-zinc-700 dark:text-zinc-300">
                ← {{ $previous->title }}
            </a>
        @else
            <div></div>
        @endif

        @if($next)
            <a href="{{ route('lesson.show', [$course->slug, $next->id]) }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold hover:opacity-90 transition-opacity">
                {{ $next->title }} →
            </a>
        @else
            <a href="{{ route('course.show', $course->slug) }}"
               class="flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">
                Back to Course →
            </a>
        @endif
    </div>

</div>