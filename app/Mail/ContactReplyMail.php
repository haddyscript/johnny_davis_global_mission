<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ContactMessage $contactMessage,
        public readonly string $replyBody,
        public readonly string $adminName,
        public readonly bool $includeOriginal,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject
            ? 'Re: ' . $this->contactMessage->subject_label
            : 'Re: Your message to ' . config('app.name');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-reply');
    }
}
