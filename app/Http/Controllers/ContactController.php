<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact', [
            'title'       => 'Contact — Johnny Davis Global Missions',
            'description' => 'Contact Johnny Davis Global Missions — Get in touch for donations, volunteering, church partnerships, and mission updates.',
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

        ContactMessage::create([
            'first_name' => $validated['firstName'],
            'last_name'  => $validated['lastName'],
            'email'      => $validated['email'],
            'subject'    => $validated['subject'] ?? null,
            'message'    => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true], 200);
        }

        return redirect()
            ->route('contact')
            ->with('contact_success', true);
    }
}
