<div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5"
     x-data="{
         displayed: 0,
         target: {{ $percentage }}
     }"
     x-init="setTimeout(() => displayed = target, 200)"
     {{-- Re-animate when Livewire re-renders after lesson-completed event --}}
     wire:key="progress-bar-{{ $courseId }}">

    {{-- Top row --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Course Progress</span>

            @if($percentage === 100)
                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-semibold">
                    🎉 Complete
                </span>
            @endif
        </div>

        <span class="text-sm font-bold tabular-nums transition-colors"
              :class="displayed === 100 ? 'text-emerald-500' : 'text-amber-500'"
              x-text="displayed + '%'">
        </span>
    </div>

    {{-- Bar --}}
    <div class="h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-700 ease-out"
             :class="displayed === 100 ? 'bg-emerald-400' : 'bg-amber-400'"
             :style="'width: ' + displayed + '%'">
        </div>
    </div>

    {{-- Lesson count --}}
    <p class="text-xs text-zinc-400 mt-2">
        {{ $completed }} of {{ $total }} lessons completed
    </p>

</div>