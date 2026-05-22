@php
$firstName   = e($firstName);
$lastName    = e($lastName);
$senderName  = trim($firstName . ' ' . $lastName);
$subjectLabel = e($subjectLabel ?: 'General Inquiry');
$submittedAt = e($submittedAt);
$messageBody = nl2br(e($message));

$body  = '<p style="margin:0 0 20px;font-size:15px;color:#374151;">A new message was submitted through the website contact form.</p>';
$body .= '<div style="padding:20px 24px;background:#f8fafc;border-left:4px solid #e2e8f0;border-radius:0 12px 12px 0;margin-bottom:24px;">';
$body .= '<p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#0f172a;">Contact details</p>';
$body .= '<p style="margin:0 0 4px;font-size:14px;color:#475569;"><strong>Name:</strong> ' . $senderName . '</p>';
$body .= '<p style="margin:0 0 4px;font-size:14px;color:#475569;"><strong>Email:</strong> ' . e($email) . '</p>';
$body .= '<p style="margin:0 0 4px;font-size:14px;color:#475569;"><strong>Subject:</strong> ' . $subjectLabel . '</p>';
$body .= '<p style="margin:0;font-size:14px;color:#475569;"><strong>Submitted:</strong> ' . $submittedAt . '</p>';
$body .= '</div>';
$body .= '<div style="padding:20px 24px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:24px;">';
$body .= '<p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#0f172a;">Message</p>';
$body .= '<div style="font-size:14px;line-height:1.8;color:#334155;">' . $messageBody . '</div>';
$body .= '</div>';
$body .= '<p style="margin:0;font-size:15px;color:#374151;">Reply directly to the sender using the email address above.</p>';
@endphp

@include('emails.layouts.brand-template', [
    'subject' => 'New contact message from ' . $senderName,
    'body'    => $body,
])
