<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Services\EmailService;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('email',      'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subscribers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => NewsletterSubscriber::count(),
            'active'   => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get(['id', 'name', 'subject', 'variables']);

        return view('admin.subscribers.index', compact('subscribers', 'stats', 'templates'));
    }

    /**
     * Return a JSON-paginated list of active subscribers for the bulk-email modal.
     */
    public function fetch(Request $request)
    {
        $query = NewsletterSubscriber::where('is_active', true)->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%");
            });
        }

        $subscribers = $query->paginate(50);

        return response()->json([
            'data'         => $subscribers->map(fn ($s) => [
                'id'         => $s->id,
                'first_name' => $s->first_name,
                'email'      => $s->email,
            ]),
            'total'        => $subscribers->total(),
            'current_page' => $subscribers->currentPage(),
            'last_page'    => $subscribers->lastPage(),
        ]);
    }

    /**
     * Send a bulk email to the selected subscribers using a chosen template.
     */
    public function bulkEmail(Request $request, EmailService $emailService)
    {
        $request->validate([
            'template_id'      => ['required', 'exists:email_templates,id'],
            'subscriber_ids'   => ['required', 'array', 'min:1'],
            'subscriber_ids.*' => ['integer', 'exists:newsletter_subscribers,id'],
            'custom_data'      => ['sometimes', 'array'],
            'custom_data.*'    => ['nullable', 'string', 'max:5000'],
        ]);

        $template    = EmailTemplate::findOrFail($request->template_id);
        $subscribers = NewsletterSubscriber::whereIn('id', $request->subscriber_ids)
            ->where('is_active', true)
            ->get();

        if ($subscribers->isEmpty()) {
            return response()->json([
                'message' => 'No active subscribers found in the selection.',
            ], 422);
        }

        // Extra variables provided by the admin (e.g. {{message}}, {{unsubscribe_link}})
        $customData = $request->input('custom_data', []);

        // Auto-fill unsubscribe_link if the template uses it and the admin didn't provide one
        if (!isset($customData['unsubscribe_link']) || trim($customData['unsubscribe_link']) === '') {
            $customData['unsubscribe_link'] = route('home');
        }

        $sent   = 0;
        $failed = 0;
        $errors = [];

        foreach ($subscribers as $subscriber) {
            // Per-subscriber data merged on top of custom data
            $data = array_merge($customData, [
                'name'  => $subscriber->first_name,
                'email' => $subscriber->email,
            ]);

            $log = $emailService->sendTemplate(
                template: $template,
                toEmail:  $subscriber->email,
                toName:   $subscriber->first_name,
                data:     $data,
            );

            if ($log->isSent()) {
                $sent++;
            } else {
                $failed++;
                $errors[] = $subscriber->email . ': ' . $log->error_message;
            }
        }

        return response()->json([
            'sent'   => $sent,
            'failed' => $failed,
            'errors' => $errors,
            'total'  => $sent + $failed,
        ]);
    }

    public function toggleActive(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        return response()->json(['is_active' => $subscriber->is_active]);
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()
            ->route('admin.subscribers.index')
            ->with('success', 'Subscriber removed.');
    }
}
