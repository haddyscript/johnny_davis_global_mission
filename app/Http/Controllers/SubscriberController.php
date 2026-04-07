<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

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
}
