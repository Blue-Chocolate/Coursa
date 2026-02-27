<x-mail::message>
# Congratulations, {{ $user->name }}! 🎉

You've completed **{{ $course->title }}**.

That's a huge achievement — keep the momentum going.

<x-mail::button :url="route('home')">
Browse More Courses
</x-mail::button>

**The LearnForward Team**
</x-mail::message>