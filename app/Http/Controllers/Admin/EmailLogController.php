<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with('emailTemplate')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('subject',        'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('template')) {
            $query->where('email_template_id', $request->template);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total'  => EmailLog::count(),
            'sent'   => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
        ];

        $templates = EmailTemplate::orderBy('name')->get(['id', 'name']);

        return view('admin.email-logs.index', compact('logs', 'stats', 'templates'));
    }

    public function show(EmailLog $emailLog)
    {
        $emailLog->load('emailTemplate');

        if (request()->expectsJson()) {
            return response()->json([
                'id'               => $emailLog->id,
                'recipient_email'  => $emailLog->recipient_email,
                'recipient_name'   => $emailLog->recipient_name,
                'subject'          => $emailLog->subject,
                'status'           => $emailLog->status,
                'error_message'    => $emailLog->error_message,
                'template_name'    => optional($emailLog->emailTemplate)->name,
                'rendered_body'    => $emailLog->rendered_body,
                'created_at'       => $emailLog->created_at->format('M j, Y g:i A'),
            ]);
        }

        return view('admin.email-logs.show', compact('emailLog'));
    }

    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();

        return redirect()
            ->route('admin.email-logs.index')
            ->with('success', 'Email log entry deleted.');
    }
}
