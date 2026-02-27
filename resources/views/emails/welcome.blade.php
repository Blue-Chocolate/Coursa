<x-mail::message>
# Welcome, {{ $user->name }}! 👋

Thanks for joining **LearnForward**. Your account is ready to go.

Browse our courses and start learning today.

<x-mail::button :url="route('home')">
Browse Courses
</x-mail::button>

See you in class,
**The LearnForward Team**
</x-mail::message>