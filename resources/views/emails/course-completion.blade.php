<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background: #f9f7f2;
            color: #1a1a1a;
            padding: 40px 20px;
        }

        .wrapper {
            max-width: 780px;
            margin: 0 auto;
        }

        /* ── Congratulations card ── */
        .congrats-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 40px;
            border: 1px solid #e8e2d9;
            text-align: center;
        }

        .emoji { font-size: 48px; margin-bottom: 16px; }

        .congrats-card h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .congrats-card p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto 24px;
        }

        /* ── Certificate ── */
        .certificate {
            background: #ffffff;
            border: 3px solid #d4a843;
            border-radius: 16px;
            padding: 60px 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .certificate::before {
            content: '';
            position: absolute;
            inset: 8px;
            border: 1px solid #e8d5a3;
            border-radius: 10px;
            pointer-events: none;
        }

        .cert-label {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #d4a843;
            margin-bottom: 24px;
            font-family: 'Arial', sans-serif;
        }

        .cert-title {
            font-size: 38px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .cert-subtitle {
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 40px;
            font-family: 'Arial', sans-serif;
        }

        .cert-presented {
            font-size: 14px;
            color: #888;
            margin-bottom: 12px;
            font-family: 'Arial', sans-serif;
        }

        .cert-name {
            font-size: 36px;
            color: #d4a843;
            font-style: italic;
            margin-bottom: 32px;
            font-weight: 700;
        }

        .cert-body {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            max-width: 480px;
            margin: 0 auto 40px;
            font-family: 'Arial', sans-serif;
        }

        .cert-course {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 8px 0 32px;
        }

        .cert-divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(to right, #d4a843, #f0c060);
            margin: 0 auto 40px;
            border: none;
        }

        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid #f0e8d5;
            font-family: 'Arial', sans-serif;
        }

        .cert-footer-item {
            text-align: center;
            flex: 1;
        }

        .cert-footer-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .cert-footer-label {
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
        }

        .cert-footer-divider {
            width: 1px;
            height: 40px;
            background: #e8d5a3;
        }

        .cert-seal {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4a843, #f0c060);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 32px;
        }

        /* ── Print button ── */
        .print-section {
            text-align: center;
            margin-top: 32px;
        }

        .print-btn {
            display: inline-block;
            background: #d4a843;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Arial', sans-serif;
            letter-spacing: 0.3px;
        }

        .print-note {
            font-size: 12px;
            color: #aaa;
            margin-top: 12px;
            font-family: 'Arial', sans-serif;
        }

        /* ── Print styles ── */
        @media print {
            body { background: white; padding: 0; }
            .congrats-card { display: none; }
            .print-section { display: none; }
            .certificate { border: 3px solid #d4a843; box-shadow: none; }
            .wrapper { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    {{-- Congratulations intro card --}}
    <div class="congrats-card">
        <div class="emoji">🎉</div>
        <h1>Congratulations, {{ $user->name }}!</h1>
        <p>
            You've successfully completed <strong>{{ $course->title }}</strong>.
            Your certificate of completion is below — print it or save it as a PDF.
        </p>
    </div>

    {{-- Certificate --}}
    <div class="certificate" id="certificate">

        <div class="cert-seal">🏆</div>

        <div class="cert-label">Certificate of Completion</div>
        <h2 class="cert-title">Certificate</h2>
        <div class="cert-subtitle">of Completion</div>

        <div class="cert-presented">This certificate is proudly presented to</div>
        <div class="cert-name">{{ $user->name }}</div>

        <hr class="cert-divider">

        <div class="cert-body">
            for successfully completing the course
        </div>

        <div class="cert-course">{{ $course->title }}</div>

        <div class="cert-body">
            demonstrating commitment, dedication, and a passion for learning.
        </div>

        <div class="cert-footer">
            <div class="cert-footer-item">
                <div class="cert-footer-value">
                    {{ \Carbon\Carbon::parse($certificate->issued_at)->format('F j, Y') }}
                </div>
                <div class="cert-footer-label">Date Issued</div>
            </div>

            <div class="cert-footer-divider"></div>

            <div class="cert-footer-item">
                <div class="cert-footer-value">{{ strtoupper(substr($certificate->uuid, 0, 8)) }}</div>
                <div class="cert-footer-label">Certificate ID</div>
            </div>

            <div class="cert-footer-divider"></div>

            <div class="cert-footer-item">
                <div class="cert-footer-value">{{ $course->level->name ?? 'All Levels' }}</div>
                <div class="cert-footer-label">Level</div>
            </div>
        </div>
    </div>

    {{-- Print / Save as PDF button --}}
    <div class="print-section">
        <a href="#" class="print-btn" onclick="window.print(); return false;">
            🖨️ &nbsp; Print / Save as PDF
        </a>
        <p class="print-note">
            Use your browser's "Save as PDF" option when printing.
        </p>
    </div>

</div>

</body>
</html>