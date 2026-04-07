<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\Request;

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
