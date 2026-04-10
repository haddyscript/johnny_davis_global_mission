<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name',  'like', '%'.$request->search.'%')
                  ->orWhere('email',      'like', '%'.$request->search.'%')
                  ->orWhere('message',    'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'read');
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        $messages = $query->paginate(15)->withQueryString();

        $stats = [
            'total'  => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'read'   => ContactMessage::where('is_read', true)->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (! $contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function toggleRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => ! $contactMessage->is_read]);

        return response()->json(['is_read' => $contactMessage->is_read]);
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'reply_message'    => ['required', 'string', 'min:10', 'max:5000'],
            'include_original' => ['nullable', 'boolean'],
        ]);

        try {
            Mail::to($contactMessage->email, $contactMessage->full_name)->send(
                new ContactReplyMail(
                    contactMessage:  $contactMessage,
                    replyBody:       $validated['reply_message'],
                    adminName:       auth()->user()->name,
                    includeOriginal: (bool) ($validated['include_original'] ?? false),
                )
            );

            $contactMessage->update([
                'reply_message' => $validated['reply_message'],
                'replied_by'    => auth()->user()->name,
                'replied_at'    => now(),
                'is_read'       => true,
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Reply sent to ' . $contactMessage->email,
                'replied_at' => $contactMessage->fresh()->replied_at->format('M j, Y g:i A'),
                'replied_by' => $contactMessage->replied_by,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply. Please check your mail configuration and try again.',
            ], 500);
        }
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted.');
    }
}
