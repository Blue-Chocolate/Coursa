<div>
    @if($isEnrolled)
        {{-- Continue button — go to first incomplete lesson --}}
        <a href="/courses/{{ $course->slug }}/lessons/{{ $course->lessons->first()?->id }}"
           class="block w-full text-center px-5 py-3 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 font-semibold hover:opacity-90 transition-opacity">
            Continue Learning →
        </a>
    @else
        <button
            wire:click="enroll"
            wire:loading.attr="disabled"
            class="w-full px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-semibold transition-colors disabled:opacity-60"
        >
            <span wire:loading.remove>
                {{ $course->price > 0 ? 'Enroll — $' . number_format($course->price, 2) : 'Enroll for Free' }}
            </span>
            <span wire:loading>Enrolling...</span>
        </button>
    @endif

    @if(session('error'))
        <p class="mt-3 text-sm text-red-500 text-center">{{ session('error') }}</p>
    @endif
</div>