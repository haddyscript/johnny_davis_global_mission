<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Mail\TemplateMail;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'contact')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('contact', [
            'title'       => $cms->text('meta', 'title', 'Contact — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Contact Johnny Davis Global Missions — Get in touch for donations, volunteering, church partnerships, and mission updates.'),
            'cms'         => $cms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'lastName'  => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255'],
            'subject'   => ['nullable', 'string', 'in:general,donation,volunteer,partnership,disaster,other'],
            'message'   => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $contactMessage = ContactMessage::create([
            'first_name' => $validated['firstName'],
            'last_name'  => $validated['lastName'],
            'email'      => $validated['email'],
            'subject'    => $validated['subject'] ?? null,
            'message'    => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        try {
            $adminEmail = 'info@johnnydavisglobalmissions.org';
            $subjectLabel = match ($validated['subject'] ?? '') {
                'donation'    => 'Donation Question',
                'volunteer'   => 'Volunteer Opportunities',
                'partnership' => 'Church Partnership',
                'disaster'    => 'Disaster Relief Coordination',
                'other'       => 'Other',
                default       => 'General Inquiry',
            };
            $senderName = trim($validated['firstName'] . ' ' . $validated['lastName']);

            $bodyHtml = View::make('emails.contact-notification', [
                'firstName'    => $validated['firstName'],
                'lastName'     => $validated['lastName'],
                'email'        => $validated['email'],
                'subjectLabel' => $subjectLabel,
                'message'      => $validated['message'],
                'submittedAt'  => now()->format('M j, Y g:i A'),
            ])->render();

            $mailable = (new TemplateMail('New contact message from ' . $senderName, $bodyHtml))
                ->replyTo($validated['email'], $senderName);

            Mail::to($adminEmail)->send($mailable);
        } catch (\Throwable $exception) {
            Log::error('ContactController: failed to send contact notification email', [
                'error' => $exception->getMessage(),
                'email' => $validated['email'],
                'name'  => $senderName,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true], 200);
        }

        return redirect()
            ->route('contact')
            ->with('contact_success', true);
    }
}
