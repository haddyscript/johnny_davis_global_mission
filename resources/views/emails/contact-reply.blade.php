@php
$firstName      = e($contactMessage->first_name);
$senderEmail    = e($contactMessage->email);
$subjectLabel   = e($contactMessage->subject_label);
$originalMsg    = e($contactMessage->message);
$originalDate   = $contactMessage->created_at->format('M j, Y \a\t g:i A');
$replyText      = nl2br(e($replyBody));
$senderName     = e($adminName);
$appName        = e(config('app.name'));

$body  = '<p style="margin:0 0 20px;font-size:15px;color:#374151;">Dear ' . $firstName . ',</p>';
$body .= '<p style="margin:0 0 20px;font-size:15px;color:#374151;">Thank you for contacting us. Please find our reply below.</p>';

$body .= '<div style="background:#f0fdf9;border-left:4px solid #14b8a6;border-radius:0 12px 12px 0;padding:20px 24px;margin:0 0 28px;">';
$body .= '<p style="margin:0 0 10px;font-size:12px;font-weight:700;color:#0f766e;text-transform:uppercase;letter-spacing:.06em;">Reply from ' . $appName . '</p>';
$body .= '<div style="font-size:15px;line-height:1.75;color:#1e293b;">' . $replyText . '</div>';
$body .= '</div>';

if ($includeOriginal) {
    $body .= '<div style="border-top:1px solid #e8e8ea;padding-top:20px;margin-top:4px;">';
    $body .= '<p style="margin:0 0 10px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;">Your original message &mdash; ' . $originalDate . '</p>';
    $body .= '<div style="padding:16px 20px;background:#f8fafc;border-left:4px solid #e2e8f0;border-radius:0 8px 8px 0;font-size:14px;line-height:1.75;color:#64748b;">' . nl2br(e($contactMessage->message)) . '</div>';
    $body .= '</div>';
}

$body .= '<p style="margin:28px 0 0;font-size:15px;color:#374151;">Best regards,<br><strong>' . $senderName . '</strong><br>';
$body .= '<span style="color:#667085;font-size:13px;">' . $appName . '</span></p>';
@endphp

@include('emails.layouts.brand-template', [
    'subject' => 'Re: ' . $subjectLabel,
    'body'    => $body,
])
