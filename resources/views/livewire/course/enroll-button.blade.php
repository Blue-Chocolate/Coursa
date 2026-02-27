<div>
    @if($isEnrolled)
        <a href="{{ route('lesson.show', [$course->slug, $course->lessons->first()?->id]) }}"
           class="block w-full text-center px-5 py-3 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 font-semibold hover:opacity-90 transition-opacity">
            Continue Learning →
        </a>
    @else
        <button wire:click="enroll" wire:loading.attr="disabled"
                class="w-full px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-semibold transition-colors disabled:opacity-60">
            <span wire:loading.remove>
                {{ $course->price > 0 ? 'Enroll — $' . number_format($course->price, 2) : 'Enroll for Free' }}
            </span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Enrolling...
            </span>
        </button>
    @endif

    @error('enroll')
        <p class="mt-3 text-sm text-red-500 text-center">{{ $message }}</p>
    @enderror
</div>