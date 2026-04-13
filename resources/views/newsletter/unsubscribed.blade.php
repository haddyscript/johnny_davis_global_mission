<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($status === 'unsubscribed') Unsubscribed
        @elseif($status === 'already_unsubscribed') Already Unsubscribed
        @else Email Not Found
        @endif
        — {{ config('app.name') }}
    </title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Inter, 'Segoe UI', Arial, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #1e293b;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(15,23,42,.10);
            max-width: 520px;
            width: 100%;
            overflow: hidden;
        }
        .card-header {
            background: #0f172a;
            padding: 32px 40px;
            text-align: center;
        }
        .card-header img { width: 140px; height: auto; }
        .card-body {
            padding: 44px 40px 40px;
            text-align: center;
        }
        .icon {
            font-size: 52px;
            display: block;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 0;
        }
        .email-chip {
            display: inline-block;
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 600;
            font-size: 14px;
            padding: 4px 12px;
            border-radius: 20px;
            margin: 8px 0 0;
        }
        .card-footer {
            border-top: 1px solid #f1f5f9;
            padding: 20px 40px;
            text-align: center;
        }
        .home-link {
            display: inline-block;
            background: #0f766e;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            transition: background .2s;
        }
        .home-link:hover { background: #0d6560; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}">
    </div>
    <div class="card-body">
        @if($status === 'unsubscribed')
            <span class="icon">✅</span>
            <h1>You've been unsubscribed</h1>
            <p>
                You will no longer receive email updates from
                <strong>{{ config('app.name') }}</strong>.
            </p>
            @if($email)
                <span class="email-chip">{{ $email }}</span>
            @endif
            <p style="margin-top:16px;font-size:13px;color:#94a3b8;">
                Changed your mind? You can re-subscribe any time from our website.
            </p>

        @elseif($status === 'already_unsubscribed')
            <span class="icon">ℹ️</span>
            <h1>Already unsubscribed</h1>
            <p>
                This email address is already unsubscribed from our mailing list.
                No further action is needed.
            </p>
            @if($email)
                <span class="email-chip">{{ $email }}</span>
            @endif

        @else
            <span class="icon">🔍</span>
            <h1>Email not found</h1>
            <p>
                We couldn't find a subscriber with that email address.
                The link may have expired or already been used.
            </p>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ url('/') }}" class="home-link">← Back to Website</a>
    </div>
</div>
</body>
</html>
