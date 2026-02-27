<div class="max-w-5xl mx-auto px-6 py-12">

    {{-- Hero --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12">

        <div class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs font-semibold px-2 py-1 rounded-md bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400">
                    {{ $course->level->name }}
                </span>
                <span class="text-xs text-zinc-400">{{ $course->lessons->count() }} lessons</span>
            </div>

            <h1 class="text-3xl font-bold leading-tight mb-4 text-zinc-900 dark:text-zinc-100">{{ $course->title }}</h1>

            @if($course->description)
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $course->description }}</p>
            @endif

            {{-- Progress bar (enrolled users only) --}}
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
                             :style="'width: ' + pct + '%'"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar card --}}
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
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">${{ number_format($course->price, 2) }}</p>
            @endif

            <livewire:course.enroll-button :course="$course" :is-enrolled="$isEnrolled" />
        </div>
    </div>

  {{-- Lesson accordion --}}
<div x-data="{
    open: null,
    completedIds: {{ $completedIds->values()->toJson() }},
    toggle(i) { this.open = this.open === i ? null : i }
}">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Course Content</h2>
        <span class="text-sm text-zinc-400">
            {{ $course->lessons->count() }} lessons
            @if($isEnrolled)
                ·
                <span class="text-amber-500 font-semibold">{{ $completionPct }}% complete</span>
            @endif
        </span>
    </div>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden divide-y divide-zinc-200 dark:divide-zinc-800">
        @foreach($course->lessons as $index => $lesson)
            @php
                $isAccessible = $lesson->is_free_preview || $isEnrolled;
            @endphp

            <div :class="open === {{ $index }} ? 'bg-amber-50 dark:bg-amber-950/20' : ''">

                {{-- Header row --}}
                <button
                    @click="toggle({{ $index }})"
                    class="w-full flex items-center gap-4 px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors text-left group"
                >
                    {{-- Completion indicator --}}
                    <span
                        class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300"
                        :class="completedIds.includes({{ $lesson->id }})
                            ? 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400'
                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400'"
                    >
                        <span x-show="completedIds.includes({{ $lesson->id }})">✓</span>
                        <span x-show="!completedIds.includes({{ $lesson->id }})">{{ $index + 1 }}</span>
                    </span>

                    {{-- Title --}}
                    <span class="flex-1 font-medium text-sm text-zinc-800 dark:text-zinc-200 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 transition-colors">
                        {{ $lesson->title }}
                    </span>

                    {{-- Meta + chevron --}}
                    <div class="flex items-center gap-3 text-xs text-zinc-400 flex-shrink-0">
                        @if($lesson->is_free_preview)
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-semibold">
                                Preview
                            </span>
                        @elseif(!$isEnrolled)
                            <span title="Enroll to unlock">🔒</span>
                        @endif

                        @if($lesson->duration_seconds)
                            <span>{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                        @endif

                        <svg
                            class="w-4 h-4 transition-transform duration-300"
                            :class="open === {{ $index }} ? 'rotate-180 text-amber-500' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Expandable body --}}
                <div
                    x-show="open === {{ $index }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="px-5 pb-4 pl-16"
                >
                    @if($isAccessible)
                        <a
                            href="{{ route('lesson.show', [$course->slug, $lesson->id]) }}"
                            class="inline-flex items-center gap-2 text-sm text-amber-500 hover:text-amber-400 font-semibold mt-1 transition-colors"
                        >
                            ▶
                            <span
                                x-text="completedIds.includes({{ $lesson->id }}) ? 'Rewatch Lesson' : 'Watch Lesson'"
                            ></span>
                        </a>
                    @else
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Enroll in this course to unlock this lesson.
                        </p>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
</div>