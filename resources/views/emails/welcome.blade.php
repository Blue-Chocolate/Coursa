<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="color: #f59e0b;">Welcome, {{ $user->name }}! 👋</h1>
    <p>Thanks for joining <strong>LearnForward</strong>. Your account is ready.</p>
    <p>
        <a href="{{ route('home') }}"
           style="display:inline-block; background:#f59e0b; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:bold;">
            Browse Courses
        </a>
    </p>
    <p style="color:#666; font-size:14px;">The LearnForward Team</p>
</body>
</html>