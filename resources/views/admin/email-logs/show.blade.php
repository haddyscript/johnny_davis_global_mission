@extends('layouts.admin')

@section('title', 'Email Log #' . $emailLog->id)
@section('page-title', 'Email Log #' . $emailLog->id)

@section('content')

<div class="admin-card" style="max-width:860px;">

    {{-- Header bar --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:28px;">
        <div>
            <h2 style="margin:0 0 6px;font-size:18px;font-weight:700;color:var(--text-dark);">
                {{ $emailLog->subject }}
            </h2>
            <p style="margin:0;font-size:13px;color:var(--text-muted);">
                To:
                @if($emailLog->recipient_name)
                    <strong>{{ $emailLog->recipient_name }}</strong> &lt;{{ $emailLog->recipient_email }}&gt;
                @else
                    {{ $emailLog->recipient_email }}
                @endif
                &nbsp;·&nbsp;
                {{ $emailLog->created_at->format('M j, Y g:i A') }}
            </p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-shrink:0;">
            @if($emailLog->status === 'sent')
                <span class="el-status-badge el-status-sent">✅ Sent</span>
            @else
                <span class="el-status-badge el-status-failed">❌ Failed</span>
            @endif
            <a href="{{ route('admin.email-logs.index') }}"
               style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:var(--surface-strong);color:var(--text-dark);text-decoration:none;border:1px solid var(--border);">
               ← Back
            </a>
        </div>
    </div>

    {{-- Meta grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-bottom:28px;">
        <div class="el-detail-block">
            <div class="el-detail-label">Template Used</div>
            <div class="el-detail-value">{{ optional($emailLog->emailTemplate)->name ?? '—' }}</div>
        </div>
        <div class="el-detail-block">
            <div class="el-detail-label">Status</div>
            <div class="el-detail-value">{{ ucfirst($emailLog->status) }}</div>
        </div>
        <div class="el-detail-block">
            <div class="el-detail-label">Sent At</div>
            <div class="el-detail-value">{{ $emailLog->created_at->format('M j, Y g:i A') }}</div>
        </div>
    </div>

    {{-- Error message (if failed) --}}
    @if($emailLog->status === 'failed' && $emailLog->error_message)
    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <div style="font-size:13px;font-weight:700;color:#dc2626;margin-bottom:4px;">Error Details</div>
        <code style="font-size:13px;color:#b91c1c;word-break:break-all;">{{ $emailLog->error_message }}</code>
    </div>
    @endif

    {{-- Rendered email preview --}}
    <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;">
        <div style="padding:12px 18px;background:var(--surface-strong);border-bottom:1px solid var(--border);font-size:13px;font-weight:600;color:var(--text-dark);">
            Email Preview
        </div>
        <iframe id="email-preview-frame"
                style="width:100%;border:none;min-height:540px;display:block;"
                title="Email content preview"></iframe>
    </div>

    {{-- Delete --}}
    <div style="margin-top:24px;display:flex;justify-content:flex-end;">
        <form method="POST" action="{{ route('admin.email-logs.destroy', $emailLog) }}"
              onsubmit="return confirm('Permanently delete this log entry?')">
            @csrf @method('DELETE')
            <button type="submit"
                style="padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;cursor:pointer;">
                🗑️ Delete Log
            </button>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<style>
.el-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600;
}
.el-status-sent   { background: rgba(16,185,129,.1); color: #059669; }
.el-status-failed { background: rgba(239,68,68,.1);  color: #dc2626; }

.el-detail-block {
    background: var(--surface-strong); border-radius: 12px; padding: 14px 16px;
}
.el-detail-label {
    font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-muted); margin-bottom: 5px;
}
.el-detail-value {
    font-size: 14px; font-weight: 600; color: var(--text-dark);
}
</style>
<script>
(function () {
    var iframe = document.getElementById('email-preview-frame');
    var doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write({!! json_encode($emailLog->rendered_body) !!});
    doc.close();
})();
</script>
@endpush
