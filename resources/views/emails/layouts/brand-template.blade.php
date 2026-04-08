<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>{{ $subject ?? config('app.name') }}</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style type="text/css">
        /* Reset */
        *, *::before, *::after { box-sizing: border-box; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        a { text-decoration: none; }

        /* Base */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f0f4f8;
            font-family: Inter, 'Segoe UI', Arial, Helvetica, sans-serif;
            color: #111827;
        }

        /* Body content typography */
        .email-body-content h1 { font-size: 24px; font-weight: 700; margin: 0 0 16px; color: #0f172a; line-height: 1.3; }
        .email-body-content h2 { font-size: 20px; font-weight: 700; margin: 0 0 14px; color: #0f172a; line-height: 1.3; }
        .email-body-content h3 { font-size: 16px; font-weight: 600; margin: 0 0 12px; color: #0f172a; line-height: 1.4; }
        .email-body-content p  { margin: 0 0 16px; }
        .email-body-content p:last-child { margin-bottom: 0; }
        .email-body-content ul, .email-body-content ol { margin: 0 0 16px; padding-left: 24px; }
        .email-body-content li { margin-bottom: 6px; }
        .email-body-content a  { color: #0f766e; text-decoration: underline; }
        .email-body-content blockquote {
            margin: 0 0 16px; padding: 12px 20px;
            border-left: 4px solid #14b8a6; background: #f0fdf9; border-radius: 0 8px 8px 0;
            color: #374151; font-style: italic;
        }
        .email-body-content strong { font-weight: 700; }
        .email-body-content em { font-style: italic; }
        .email-body-content code {
            background: #f1f5f9; color: #0f766e;
            padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 13px;
        }

        /* Responsive */
        @media only screen and (max-width: 640px) {
            .email-wrapper { padding: 24px 12px !important; }
            .email-card    { width: 100% !important; }
            .email-header  { padding: 24px 20px !important; }
            .email-body    { padding: 28px 20px !important; }
            .email-footer  { padding: 20px !important; }
            .logo-img      { width: 140px !important; max-width: 140px !important; }
        }
    </style>
</head>

<body style="margin:0;padding:0;background-color:#f0f4f8;">

{{-- Preheader text (hidden, for email client preview) --}}
<div style="display:none;font-size:1px;color:#f0f4f8;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
    {{ strip_tags(substr($body, 0, 140)) }}
</div>

{{-- ━━ Outer wrapper ━━ --}}
<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="background-color:#f0f4f8;width:100%;min-height:100vh;"
>
<tr>
<td align="center" class="email-wrapper" style="padding:48px 20px;">

    {{-- ━━ Email card (max 600px) ━━ --}}
    <table
        role="presentation"
        class="email-card"
        width="600"
        cellpadding="0"
        cellspacing="0"
        border="0"
        style="max-width:600px;width:100%;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(15,23,42,0.10);"
    >

        {{-- ━━ Header / Logo ━━ --}}
        <tr>
            <td
                class="email-header"
                align="center"
                style="background-color:#0f172a;padding:36px 40px;border-radius:20px 20px 0 0;"
            >
                <img
                    src="{{ asset('images/logo.webp') }}"
                    alt="{{ config('app.name') }}"
                    width="180"
                    class="logo-img"
                    style="max-width:180px;width:180px;height:auto;display:block;margin:0 auto;"
                >
                @if(!empty($preheaderTag))
                <p style="margin:16px 0 0;font-size:13px;color:rgba(255,255,255,0.55);letter-spacing:0.08em;text-transform:uppercase;">
                    {{ $preheaderTag }}
                </p>
                @endif
            </td>
        </tr>

        {{-- ━━ Subject banner ━━ --}}
        @if(!empty($subject))
        <tr>
            <td
                style="background-color:#0f766e;padding:18px 40px;text-align:center;"
            >
                <p style="margin:0;font-size:15px;font-weight:600;color:#ffffff;letter-spacing:0.01em;">
                    {{ $subject }}
                </p>
            </td>
        </tr>
        @endif

        {{-- ━━ Body content ━━ --}}
        <tr>
            <td
                class="email-body email-body-content"
                style="background-color:#ffffff;padding:44px 44px 36px;font-size:15px;line-height:1.75;color:#374151;"
            >
                {!! $body !!}
            </td>
        </tr>

        {{-- ━━ CTA divider ━━ --}}
        <tr>
            <td style="background-color:#ffffff;padding:0 44px 36px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="border-top:1px solid #e8e8ea;"></td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- ━━ Footer ━━ --}}
        <tr>
            <td
                class="email-footer"
                align="center"
                style="background-color:#f8fafc;padding:28px 40px 32px;border-top:1px solid #e8e8ea;border-radius:0 0 20px 20px;"
            >
                {{-- Logo small --}}
                <img
                    src="{{ asset('images/logo.webp') }}"
                    alt="{{ config('app.name') }}"
                    width="80"
                    style="max-width:80px;width:80px;height:auto;display:block;margin:0 auto 16px;opacity:0.7;"
                >

                {{-- Org name --}}
                <p style="margin:0 0 8px;font-weight:700;font-size:14px;color:#0f172a;letter-spacing:0.01em;">
                    {{ config('app.name') }}
                </p>

                {{-- Contact & website --}}
                <p style="margin:0 0 14px;font-size:13px;color:#667085;line-height:1.6;">
                    <a href="mailto:{{ config('mail.from.address', 'info@jdgm.org') }}"
                       style="color:#0f766e;text-decoration:none;">
                        {{ config('mail.from.address', 'info@jdgm.org') }}
                    </a>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    <a href="{{ config('app.url') }}"
                       style="color:#0f766e;text-decoration:none;">
                        {{ parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url') }}
                    </a>
                </p>

                {{-- Social icons (inline SVG via emoji fallback for email) --}}
                <p style="margin:0 0 16px;">
                    <a href="#" style="display:inline-block;margin:0 5px;text-decoration:none;" title="Facebook">
                        <span style="display:inline-block;width:32px;height:32px;background:#1877f2;border-radius:8px;line-height:32px;text-align:center;font-size:14px;color:#fff;">f</span>
                    </a>
                    <a href="#" style="display:inline-block;margin:0 5px;text-decoration:none;" title="Instagram">
                        <span style="display:inline-block;width:32px;height:32px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);border-radius:8px;line-height:32px;text-align:center;font-size:14px;color:#fff;">&#9679;</span>
                    </a>
                    <a href="#" style="display:inline-block;margin:0 5px;text-decoration:none;" title="YouTube">
                        <span style="display:inline-block;width:32px;height:32px;background:#ff0000;border-radius:8px;line-height:32px;text-align:center;font-size:14px;color:#fff;">&#9654;</span>
                    </a>
                </p>

                {{-- Copyright --}}
                <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.6;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                    You received this email because you are a member of our community.
                </p>
            </td>
        </tr>

    </table>
    {{-- /email-card --}}

</td>
</tr>
</table>
{{-- /outer wrapper --}}

</body>
</html>
