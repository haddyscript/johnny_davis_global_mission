<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255'],
        ]);

        // Duplicate check — return friendly message instead of error
        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active) {
                return response()->json([
                    'already_subscribed' => true,
                    'message'            => "You're already subscribed! We'll keep sending updates your way.",
                ], 200);
            }

            // Re-activate if previously unsubscribed
            $existing->update(['first_name' => $validated['first_name'], 'is_active' => true]);

            return response()->json(['success' => true, 'reactivated' => true], 200);
        }

        NewsletterSubscriber::create([
            'first_name' => $validated['first_name'],
            'email'      => $validated['email'],
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['success' => true], 201);
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber && $subscriber->is_active) {
            $subscriber->update(['is_active' => false]);
            $status = 'unsubscribed';
        } elseif ($subscriber && ! $subscriber->is_active) {
            $status = 'already_unsubscribed';
        } else {
            $status = 'not_found';
        }

        return view('newsletter.unsubscribed', compact('status', 'email'));
    }

    /**
     * Generate a signed unsubscribe URL for a given email address.
     * Used by EmailService to inject {{unsubscribe_link}} automatically.
     */
    public static function signedUnsubscribeUrl(string $email): string
    {
        return URL::signedRoute('newsletter.unsubscribe', ['email' => $email]);
    }
}
