<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailTemplate::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',    'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $templates = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => EmailTemplate::count(),
            'active'   => EmailTemplate::where('is_active', true)->count(),
            'inactive' => EmailTemplate::where('is_active', false)->count(),
        ];

        return view('admin.email-templates.index', compact('templates', 'stats'));
    }

    public function create()
    {
        return view('admin.email-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'body'      => 'required|string',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['variables']  = $this->parseVariables($request->input('variables'));

        EmailTemplate::create($validated);

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'Email template "' . $validated['name'] . '" created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'body'      => 'required|string',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['variables']  = $this->parseVariables($request->input('variables'));

        $emailTemplate->update($validated);

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'Template "' . $emailTemplate->name . '" updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $name = $emailTemplate->name;
        $emailTemplate->delete();

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'Template "' . $name . '" deleted.');
    }

    public function toggle(EmailTemplate $emailTemplate)
    {
        $emailTemplate->update(['is_active' => ! $emailTemplate->is_active]);

        return response()->json(['is_active' => $emailTemplate->is_active]);
    }

    /**
     * Render a branded preview for an unsaved template (create/edit forms).
     */
    public function previewRender(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'body'    => 'nullable|string',
        ]);

        $template = new EmailTemplate([
            'subject' => $request->input('subject', ''),
            'body'    => $request->input('body', ''),
        ]);

        $subject = $template->renderWithSampleData($template->subject ?? '');
        $body    = $template->renderWithSampleData($template->body ?? '');

        $brandedHtml = view('emails.layouts.brand-template', compact('subject', 'body'))->render();

        return response()->json([
            'subject'      => $subject,
            'branded_html' => $brandedHtml,
        ]);
    }

    public function preview(EmailTemplate $emailTemplate)
    {
        $subject = $emailTemplate->renderWithSampleData($emailTemplate->subject);
        $body    = $emailTemplate->renderWithSampleData($emailTemplate->body);

        $brandedHtml = view('emails.layouts.brand-template', [
            'subject' => $subject,
            'body'    => $body,
        ])->render();

        return response()->json([
            'name'         => $emailTemplate->name,
            'subject_raw'  => $emailTemplate->subject,
            'subject'      => $subject,
            'branded_html' => $brandedHtml,
        ]);
    }

    private function parseVariables(?string $raw): ?array
    {
        if (empty($raw)) {
            return null;
        }

        $lines = array_filter(array_map('trim', explode("\n", $raw)));

        if (empty($lines)) {
            return null;
        }

        $vars = [];
        foreach ($lines as $line) {
            [$key, $description] = array_pad(explode(':', $line, 2), 2, '');
            $key = trim($key);
            if ($key) {
                $vars[$key] = trim($description);
            }
        }

        return $vars ?: null;
    }
}
