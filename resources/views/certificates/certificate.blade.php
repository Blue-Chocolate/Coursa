<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate — {{ $course->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background: #f9f7f2;
            padding: 40px 20px;
        }

        .wrapper { max-width: 860px; margin: 0 auto; }

        .certificate {
            background: #fff;
            border: 3px solid #d4a843;
            border-radius: 12px;
            padding: 60px;
            text-align: center;
            position: relative;
        }

        .certificate::before {
            content: '';
            position: absolute;
            inset: 8px;
            border: 1px solid #e8d5a3;
            border-radius: 8px;
            pointer-events: none;
        }

        .seal { font-size: 56px; margin-bottom: 16px; }

        .cert-label {
            font-size: 11px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #d4a843;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }

        .cert-title { font-size: 42px; font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }

        .cert-subtitle {
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 32px;
            font-family: Arial, sans-serif;
        }

        .presented-to { font-size: 13px; color: #888; margin-bottom: 8px; font-family: Arial, sans-serif; }

        .recipient-name {
            font-size: 40px;
            color: #d4a843;
            font-style: italic;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .divider { width: 60px; height: 2px; background: #d4a843; margin: 0 auto 24px; border: none; }

        .cert-body { font-size: 14px; color: #555; font-family: Arial, sans-serif; margin-bottom: 8px; }

        .course-name { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 24px; }

        .footer {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
            padding-top: 28px;
            border-top: 1px solid #f0e8d5;
            font-family: Arial, sans-serif;
        }

        .footer-item { text-align: center; }

        .footer-value { font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; }

        .footer-uuid { font-size: 10px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; letter-spacing: 0.5px; word-break: break-all; max-width: 260px; }

        .footer-label {
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
        }

        /* Share + Print bar */
        .action-bar {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-gold { background: #d4a843; color: #fff; }
        .btn-outline { background: #fff; color: #1a1a1a; border: 1.5px solid #e0d5c0; }
        .btn-outline:hover { border-color: #d4a843; color: #d4a843; }

        .verify-bar {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
            font-family: Arial, sans-serif;
        }

        .verify-bar a { color: #d4a843; text-decoration: none; word-break: break-all; }

        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none; }
            .verify-bar { display: none; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="certificate" id="certificate">
        <div class="seal">🏆</div>
        <div class="cert-label">Certificate of Completion</div>
        <h1 class="cert-title">Certificate</h1>
        <div class="cert-subtitle">of Completion</div>

        <div class="presented-to">This certificate is proudly presented to</div>
        <div class="recipient-name">{{ $user->name }}</div>

        <hr class="divider">

        <div class="cert-body">for successfully completing the course</div>
        <div class="course-name">{{ $course->title }}</div>
        <div class="cert-body">demonstrating commitment, dedication, and a passion for learning.</div>

        <div class="footer">
            <div class="footer-item">
                <div class="footer-value">
                    {{ \Carbon\Carbon::parse($certificate->issued_at)->format('F j, Y') }}
                </div>
                <div class="footer-label">Date Issued</div>
            </div>

            <div class="footer-item">
                <div class="footer-uuid">{{ strtoupper($certificate->uuid) }}</div>
                <div class="footer-label">Certificate ID</div>
            </div>

            <div class="footer-item">
                <div class="footer-value">{{ $course->level->name ?? 'All Levels' }}</div>
                <div class="footer-label">Level</div>
            </div>
        </div>
    </div>

    {{-- Action bar — hidden on print --}}
    <div class="action-bar">
        <button class="btn btn-gold" onclick="window.print()">
            🖨️ Print / Save as PDF
        </button>
        <button class="btn btn-outline" onclick="navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!'))">
            🔗 Copy Shareable Link
        </button>
    </div>

    <div class="verify-bar">
        Verify this certificate at:
        <a href="{{ route('certificate.show', $certificate->uuid) }}">
            {{ route('certificate.show', $certificate->uuid) }}
        </a>
    </div>

</div>
</body>
</html>