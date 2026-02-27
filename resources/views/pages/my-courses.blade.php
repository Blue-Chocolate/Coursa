<div class="max-w-5xl mx-auto px-6 py-14">

    {{-- Header --}}
    <div class="mb-10">
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-2">Dashboard</p>
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">My Learning</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1 text-sm">{{ $enrollments->count() }} course{{ $enrollments->count() !== 1 ? 's' : '' }} enrolled</p>
    </div>

    {{-- Empty state --}}
    @if($enrollments->isEmpty())
        <div class="text-center py-24 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700">
            <p class="text-5xl mb-4">📚</p>
            <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">No courses yet</h2>
            <p class="text-sm text-zinc-400 mt-1 mb-6">Browse the catalog and enroll in your first course.</p>
            <a href="/"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold transition-colors">
                Browse Courses →
            </a>
        </div>

    @else
        {{-- Stats row --}}
        @php
            $completedCount = $enrollments->where('is_course_completed', true)->count();
            $avgPct         = $enrollments->avg('completion_pct');
        @endphp

        <div class="grid grid-cols-3 gap-4 mb-10">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $enrollments->count() }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Enrolled</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <p class="text-2xl font-bold text-emerald-500">{{ $completedCount }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Completed</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <p class="text-2xl font-bold text-amber-500">{{ round($avgPct) }}%</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Avg Progress</p>
            </div>
        </div>

        {{-- Course cards --}}
        <div class="space-y-4">
            @foreach($enrollments as $enrollment)
                @php $course = $enrollment->course; @endphp

                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-amber-300 dark:hover:border-amber-700 transition-colors"
                     x-data="{
                         pct: 0,
                         target: {{ $enrollment->completion_pct }}
                     }"
                     x-init="setTimeout(() => pct = target, 300 + {{ $loop->index * 100 }})">

                    <div class="flex gap-5">

                        {{-- Thumbnail --}}
                        <div class="flex-shrink-0 w-24 h-16 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                            @if($course->image)
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                                     class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl">🎓</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-1">
                                <div>
                                    <a href="/courses/{{ $course->slug }}"
                                       class="font-semibold text-zinc-900 dark:text-zinc-100 hover:text-amber-500 transition-colors leading-snug">
                                        {{ $course->title }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 font-semibold">
                                            {{ $course->level->name }}
                                        </span>
                                        <span class="text-xs text-zinc-400">
                                            {{ $enrollment->completed_count }} / {{ $enrollment->total_lessons }} lessons
                                        </span>
                                        @if($enrollment->is_course_completed)
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-semibold">
                                                ✓ Completed
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action button --}}
                                @if($enrollment->is_course_completed)
                                    <a href="/courses/{{ $course->slug }}"
                                       class="flex-shrink-0 px-4 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:border-amber-300 hover:text-amber-500 transition-all">
                                        Review
                                    </a>
                                @elseif($enrollment->next_lesson)
                                    <a href="/courses/{{ $course->slug }}/lessons/{{ $enrollment->next_lesson->id }}"
                                       class="flex-shrink-0 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-xs font-semibold text-white transition-colors">
                                        Continue →
                                    </a>
                                @else
                                    <a href="/courses/{{ $course->slug }}/lessons/{{ $course->lessons->first()?->id }}"
                                       class="flex-shrink-0 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-xs font-semibold text-white transition-colors">
                                        Start →
                                    </a>
                                @endif
                            </div>

                            {{-- Progress bar (Alpine animated) --}}
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-zinc-400 mb-1">
                                    <span>Progress</span>
                                    <span class="font-semibold" :class="pct === 100 ? 'text-emerald-500' : 'text-amber-500'" x-text="pct + '%'"></span>
                                </div>
                                <div class="h-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700 ease-out"
                                         :class="pct === 100 ? 'bg-emerald-400' : 'bg-amber-400'"
                                         :style="'width: ' + pct + '%'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Browse more --}}
        <div class="mt-8 text-center">
            <a href="/" class="text-sm text-amber-500 hover:text-amber-400 font-semibold transition-colors">
                + Browse more courses
            </a>
        </div>
    @endif

</div>