<?php

namespace App\Services;

use App\Mail\TemplateMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Throwable;

class EmailService
{
    /**
     * Send an email using a template stored in the database.
     *
     * @param  EmailTemplate  $template  The template to use.
     * @param  string         $toEmail   Recipient email address.
     * @param  string|null    $toName    Recipient name (optional).
     * @param  array          $data      Key/value pairs to replace {{placeholders}} in subject and body.
     * @return EmailLog
     */
    public function sendTemplate(
        EmailTemplate $template,
        string $toEmail,
        ?string $toName = null,
        array $data = [],
    ): EmailLog {
        $subject      = $this->render($template->subject, $data);
        $renderedBody = $this->render($template->body, $data);
        $brandedHtml  = View::make('emails.layouts.brand-template', [
            'subject' => $subject,
            'body'    => $renderedBody,
        ])->render();

        try {
            $mailable = new TemplateMail($subject, $renderedBody);

            Mail::to($toEmail, $toName)->send($mailable);

            return $this->log($template, $toEmail, $toName, $subject, $brandedHtml, 'sent');
        } catch (Throwable $e) {
            return $this->log($template, $toEmail, $toName, $subject, $brandedHtml, 'failed', $e->getMessage());
        }
    }

    /**
     * Send an email using a template name looked up from the database.
     *
     * Throws \RuntimeException if the template is not found or inactive.
     */
    public function sendByName(
        string $templateName,
        string $toEmail,
        ?string $toName = null,
        array $data = [],
    ): EmailLog {
        $template = EmailTemplate::where('name', $templateName)
            ->where('is_active', true)
            ->firstOrFail();

        return $this->sendTemplate($template, $toEmail, $toName, $data);
    }

    /**
     * Replace {{placeholder}} tokens in a string with the given data.
     */
    public function render(string $content, array $data): string
    {
        $search  = array_map(fn ($key) => '{{' . $key . '}}', array_keys($data));
        $replace = array_values($data);

        return str_replace($search, $replace, $content);
    }

    // ──────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────

    private function log(
        EmailTemplate $template,
        string $toEmail,
        ?string $toName,
        string $subject,
        string $renderedBody,
        string $status,
        ?string $errorMessage = null,
    ): EmailLog {
        return EmailLog::create([
            'email_template_id' => $template->id,
            'recipient_email'   => $toEmail,
            'recipient_name'    => $toName,
            'subject'           => $subject,
            'rendered_body'     => $renderedBody,
            'status'            => $status,
            'error_message'     => $errorMessage,
        ]);
    }
}
