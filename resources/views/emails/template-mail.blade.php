@include('emails.layouts.brand-template', [
    'subject' => $emailSubject,
    'body'    => $renderedBody,
])
