<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f7f2; padding: 40px 20px; color: #1a1a1a; }
        .card { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 48px 40px; border: 1px solid #e8e2d9; text-align: center; }
        .emoji { font-size: 52px; margin-bottom: 20px; }
        h1 { font-size: 24px; margin-bottom: 12px; }
        p { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 16px; }
        .badge { display: inline-block; background: #fef9ec; border: 1px solid #e8d5a3; color: #d4a843; font-size: 13px; font-weight: 600; padding: 8px 20px; border-radius: 50px; margin-top: 8px; }
        .footer { margin-top: 32px; font-size: 12px; color: #bbb; }
    </style>
</head>
<body>
<div class="card">
    <div class="emoji">🎉</div>
    <h1>Congratulations, {{ $user->name }}!</h1>
    <p>You've successfully completed <strong>{{ $course->title }}</strong>.</p>
    <p>Your certificate is attached as a PDF and also available online at your personal link below.</p>

    <a href="{{ route('certificate.show', $certificate->uuid) }}" class="btn">
        View Certificate Online
    </a>

    <div class="badge">
        🏆 &nbsp; {{ strtoupper($certificate->uuid) }}
    </div>
    <div class="footer">
        Issued on {{ \Carbon\Carbon::parse($certificate->issued_at)->format('F j, Y') }}
    </div>
</div>
</body>
</html>