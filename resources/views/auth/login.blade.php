<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name', 'JDGM') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:        #14b8a6;
            --brand-dark:   #0f766e;
            --brand-light:  rgba(20,184,166,0.12);
            --panel-bg:     #0f172a;
            --panel-mid:    #1e293b;
            --surface:      #ffffff;
            --bg:           #f1f5f9;
            --text:         #0f172a;
            --text-muted:   #64748b;
            --border:       #e2e8f0;
            --danger:       #ef4444;
            --danger-light: #fef2f2;
            --danger-border:#fecaca;
            --success:      #10b981;
            --radius-card:  20px;
            --radius-input: 10px;
            --transition:   0.2s ease;
            --shadow-card:  0 24px 64px rgba(15,23,42,0.14);
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Layout ── */
        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── Left panel ── */
        .login-hero {
            background: linear-gradient(145deg, #0f172a 0%, #111827 55%, #0c1a2e 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
        }

        .login-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(20,184,166,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 10%, rgba(99,102,241,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
        }

        .hero-logo-mark {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--brand-light);
            border: 1px solid rgba(20,184,166,0.3);
            display: grid;
            place-items: center;
            font-size: 22px;
            color: var(--brand);
        }

        .hero-brand-name {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .hero-brand-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            margin-top: 2px;
        }

        .hero-body {
            position: relative;
        }

        .hero-headline {
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.8px;
            margin-bottom: 16px;
        }

        .hero-headline span {
            color: var(--brand);
        }

        .hero-tagline {
            font-size: 15px;
            color: rgba(255,255,255,0.5);
            line-height: 1.65;
            max-width: 360px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 48px;
        }

        .hero-stat {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 18px 16px;
        }

        .hero-stat-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--brand);
            letter-spacing: -0.5px;
        }

        .hero-stat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-footer {
            position: relative;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
        }

        /* ── Right panel (form) ── */
        .login-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--bg);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            padding: 44px 40px;
        }

        .card-header {
            margin-bottom: 32px;
        }

        .card-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--brand-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
            background: var(--brand-light);
            padding: 5px 12px;
            border-radius: 100px;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .card-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* ── Alert ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 24px;
            animation: slideDown 0.25s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: var(--danger-light);
            border: 1px solid var(--danger-border);
            color: #b91c1c;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-icon { flex-shrink: 0; font-size: 15px; margin-top: 1px; }

        /* ── Form fields ── */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .field-label label {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text);
        }

        .field-link {
            font-size: 12px;
            color: var(--brand-dark);
            text-decoration: none;
            font-weight: 500;
        }

        .field-link:hover { text-decoration: underline; }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
            transition: color var(--transition);
        }

        .field-input {
            width: 100%;
            padding: 12px 44px 12px 42px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: #fafafa;
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            -webkit-appearance: none;
        }

        .field-input:focus {
            border-color: var(--brand);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(20,184,166,0.15);
        }

        .field-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon {
            color: var(--brand-dark);
        }

        .field-input.is-invalid {
            border-color: var(--danger);
            background: #fffafa;
        }

        .field-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        }

        .field-input.is-valid {
            border-color: var(--success);
        }

        /* Toggle password visibility */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 15px;
            padding: 4px;
            line-height: 1;
            transition: color var(--transition);
        }

        .toggle-pw:hover { color: var(--text); }

        /* Inline validation message */
        .field-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.2s, transform 0.2s;
        }

        .field-error.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Remember Me row ── */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .custom-checkbox {
            position: relative;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }

        .custom-checkbox .checkmark {
            position: absolute;
            inset: 0;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: #fafafa;
            transition: all var(--transition);
            display: grid;
            place-items: center;
        }

        .custom-checkbox .checkmark::after {
            content: '';
            width: 5px;
            height: 9px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg) scale(0);
            transition: transform 0.15s ease;
        }

        .custom-checkbox input:checked ~ .checkmark {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }

        .custom-checkbox input:checked ~ .checkmark::after {
            transform: rotate(45deg) scale(1);
        }

        .custom-checkbox input:focus ~ .checkmark {
            box-shadow: 0 0 0 3px rgba(20,184,166,0.2);
        }

        .remember-label {
            font-size: 13.5px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 100%);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-input);
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity var(--transition), transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 4px 16px rgba(14,116,111,0.35);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover:not(:disabled) {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(14,116,111,0.4);
        }

        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Spinner */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-submit.loading .spinner { display: block; }
        .btn-submit.loading .btn-text { opacity: 0.6; }

        /* ── Form footer ── */
        .form-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12.5px;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--brand-dark);
            font-weight: 600;
            text-decoration: none;
        }

        .form-footer a:hover { text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .login-shell { grid-template-columns: 1fr; }
            .login-hero { display: none; }
            .login-form-side { padding: 32px 20px; min-height: 100vh; }
            .login-card { padding: 36px 28px; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 28px 20px; border-radius: 16px; }
            .card-title { font-size: 22px; }
        }
    </style>
</head>
<body>

<div class="login-shell">

    {{-- ── Left hero panel ── --}}
    <div class="login-hero">
        <div class="hero-brand">
            <div class="hero-logo-mark">✦</div>
            <div>
                <div class="hero-brand-name">{{ config('app.name', 'JDGM') }}</div>
                <div class="hero-brand-sub">Admin Control Panel</div>
            </div>
        </div>

        <div class="hero-body">
            <h1 class="hero-headline">
                Manage your<br>
                mission with<br>
                <span>confidence.</span>
            </h1>
            <p class="hero-tagline">
                Securely access the admin panel to manage pages, sections,
                and content blocks across the entire website.
            </p>

            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">100%</div>
                    <div class="hero-stat-label">Secure</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">CMS</div>
                    <div class="hero-stat-label">Powered</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">24/7</div>
                    <div class="hero-stat-label">Access</div>
                </div>
            </div>
        </div>

        <div class="hero-footer">
            © {{ date('Y') }} Johnny Davis Global Mission. All rights reserved.
        </div>
    </div>

    {{-- ── Right form panel ── --}}
    <div class="login-form-side">
        <div class="login-card">

            <div class="card-header">
                <div class="card-eyebrow">
                    <span>🔐</span> Admin Access
                </div>
                <h2 class="card-title">Welcome back</h2>
                <p class="card-subtitle">Sign in to your administrator account</p>
            </div>

            {{-- Session status (e.g. password reset link sent) --}}
            @if (session('status'))
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Auth error --}}
            @if ($errors->any())
                <div class="alert alert-error" id="auth-error">
                    <span class="alert-icon">⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field" id="field-email">
                    <div class="field-label">
                        <label for="email">Email address</label>
                    </div>
                    <div class="input-wrap">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            autocomplete="email"
                            autofocus
                            required
                        >
                        <span class="input-icon">✉️</span>
                    </div>
                    <div class="field-error" id="email-error">
                        <span>⚠</span> <span id="email-error-msg">Please enter a valid email address.</span>
                    </div>
                </div>

                {{-- Password --}}
                <div class="field" id="field-password">
                    <div class="field-label">
                        <label for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="field-link">Forgot password?</a>
                        @endif
                    </div>
                    <div class="input-wrap">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <span class="input-icon">🔑</span>
                        <button type="button" class="toggle-pw" id="toggle-pw" aria-label="Toggle password visibility">
                            <span id="pw-icon">👁️</span>
                        </button>
                    </div>
                    <div class="field-error" id="password-error">
                        <span>⚠</span> <span>Password must be at least 8 characters.</span>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="remember-row">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </label>
                    <label class="remember-label" for="remember">Keep me signed in</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit" id="submit-btn">
                    <div class="spinner" id="spinner"></div>
                    <span class="btn-text">Sign in to Admin Panel</span>
                </button>
            </form>

            <div class="form-footer">
                <a href="{{ url('/') }}">← Back to website</a>
            </div>

        </div>
    </div>

</div>

<script>
(function () {
    'use strict';

    const form        = document.getElementById('login-form');
    const emailInput  = document.getElementById('email');
    const pwInput     = document.getElementById('password');
    const submitBtn   = document.getElementById('submit-btn');
    const spinner     = document.getElementById('spinner');
    const togglePw    = document.getElementById('toggle-pw');
    const pwIcon      = document.getElementById('pw-icon');

    /* ── Real-time email validation ── */
    function validateEmail() {
        const val = emailInput.value.trim();
        const errorEl = document.getElementById('email-error');
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);

        if (val === '') {
            emailInput.classList.remove('is-invalid', 'is-valid');
            errorEl.classList.remove('visible');
            return true;
        }

        if (!isValid) {
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
            errorEl.classList.add('visible');
            return false;
        }

        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
        errorEl.classList.remove('visible');
        return true;
    }

    /* ── Real-time password validation ── */
    function validatePassword() {
        const val = pwInput.value;
        const errorEl = document.getElementById('password-error');

        if (val === '') {
            pwInput.classList.remove('is-invalid', 'is-valid');
            errorEl.classList.remove('visible');
            return true;
        }

        if (val.length < 8) {
            pwInput.classList.add('is-invalid');
            pwInput.classList.remove('is-valid');
            errorEl.classList.add('visible');
            return false;
        }

        pwInput.classList.remove('is-invalid');
        pwInput.classList.add('is-valid');
        errorEl.classList.remove('visible');
        return true;
    }

    emailInput.addEventListener('input', validateEmail);
    emailInput.addEventListener('blur', validateEmail);
    pwInput.addEventListener('input', validatePassword);
    pwInput.addEventListener('blur', validatePassword);

    /* ── Toggle password visibility ── */
    togglePw.addEventListener('click', function () {
        const isHidden = pwInput.type === 'password';
        pwInput.type = isHidden ? 'text' : 'password';
        pwIcon.textContent = isHidden ? '🙈' : '👁️';
    });

    /* ── Loading state on submit ── */
    form.addEventListener('submit', function (e) {
        const emailOk = validateEmail();
        const pwOk    = validatePassword();

        if (!emailOk || !pwOk) {
            e.preventDefault();
            if (!emailOk) emailInput.focus();
            else pwInput.focus();
            return;
        }

        /* Show loading */
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
    });

    /* ── Re-enable if browser back-button ── */
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }
    });

    /* ── Mark server-side invalid fields on load ── */
    @if ($errors->has('email'))
        emailInput.classList.add('is-invalid');
    @endif
    @if ($errors->has('password'))
        pwInput.classList.add('is-invalid');
    @endif
})();
</script>

</body>
</html>
