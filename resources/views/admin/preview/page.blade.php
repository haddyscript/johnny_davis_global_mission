<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview — {{ $page->name }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Preview toolbar ── */
        .preview-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .preview-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f59e0b;
            color: #1c1917;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .preview-page-name {
            color: #f1f5f9;
            font-size: 14px;
            font-weight: 600;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-page-slug {
            font-size: 12px;
            color: #64748b;
            font-weight: 400;
            margin-left: 6px;
        }

        /* Device toggles */
        .device-toggles {
            display: flex;
            gap: 4px;
            background: #0f172a;
            border-radius: 8px;
            padding: 3px;
        }

        .device-btn {
            background: none;
            border: none;
            color: #64748b;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: background .15s, color .15s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .device-btn:hover { background: #1e293b; color: #cbd5e1; }
        .device-btn.active { background: #3b82f6; color: #fff; }

        .preview-close {
            background: none;
            border: 1px solid #334155;
            color: #94a3b8;
            padding: 5px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: border-color .15s, color .15s;
            text-decoration: none;
            flex-shrink: 0;
        }

        .preview-close:hover { border-color: #64748b; color: #e2e8f0; }

        /* ── Canvas area ── */
        .preview-canvas {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 24px 16px 40px;
            transition: padding .2s;
        }

        .preview-frame {
            background: #fff;
            border-radius: 8px;
            width: 100%;
            max-width: 1280px;
            transition: max-width .25s ease;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(255,255,255,.06), 0 20px 60px rgba(0,0,0,.5);
        }

        .preview-frame.tablet  { max-width: 768px; }
        .preview-frame.mobile  { max-width: 390px; }

        /* ── Page header ── */
        .page-header {
            background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);
            color: #fff;
            padding: 40px 48px;
        }

        .page-header-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: 8px;
        }

        .page-header-title {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .page-header-desc {
            font-size: 16px;
            opacity: .85;
            line-height: 1.6;
            max-width: 600px;
        }

        .page-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 16px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .page-status.active   { background: rgba(255,255,255,.2); }
        .page-status.inactive { background: rgba(0,0,0,.2); }

        /* ── Sections ── */
        .sections-list { padding: 32px 48px; display: flex; flex-direction: column; gap: 24px; }

        .section-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .section-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-name {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .section-slug-chip {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            background: #e2e8f0;
            color: #475569;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .section-type-chip {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            margin-left: auto;
        }

        .section-order-chip {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ── Content Blocks ── */
        .blocks-grid {
            padding: 16px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 12px;
        }

        .block-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .block-header {
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .block-key {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700;
            color: #374151;
            flex: 1;
        }

        .block-type-badge {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .block-type-badge.text  { background: #dbeafe; color: #1d4ed8; }
        .block-type-badge.image { background: #fce7f3; color: #be185d; }
        .block-type-badge.link  { background: #fef3c7; color: #92400e; }
        .block-type-badge.list  { background: #d1fae5; color: #065f46; }

        .block-body { padding: 10px 12px; }

        .block-content-text {
            font-size: 13px;
            color: #374151;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .block-image-preview {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 4px;
            display: block;
        }

        .block-image-url {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
            word-break: break-all;
        }

        .block-link-url {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            word-break: break-all;
        }

        .block-link-url:hover { text-decoration: underline; }

        .block-list-items {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .block-list-items li {
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .block-list-items li::before {
            content: '•';
            color: #14b8a6;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .block-empty {
            font-size: 12px;
            color: #9ca3af;
            font-style: italic;
        }

        /* ── Empty states ── */
        .no-sections {
            padding: 48px;
            text-align: center;
            color: #94a3b8;
        }

        .no-sections-icon { font-size: 36px; margin-bottom: 12px; }
        .no-sections-text { font-size: 15px; }

        .no-blocks {
            padding: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        /* ── Responsive adjustments ── */
        @media (max-width: 600px) {
            .page-header   { padding: 24px 20px; }
            .page-header-title { font-size: 22px; }
            .sections-list { padding: 16px; }
            .blocks-grid   { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- ── Preview toolbar ── --}}
<div class="preview-bar">
    <div class="preview-badge">
        <span>👁</span> Preview Mode
    </div>

    <div class="preview-page-name">
        {{ $page->name }}
        <span class="preview-page-slug">/{{ $page->slug }}</span>
    </div>

    <div class="device-toggles" role="group" aria-label="Device preview">
        <button class="device-btn active" data-device="desktop" title="Desktop">
            🖥 Desktop
        </button>
        <button class="device-btn" data-device="tablet" title="Tablet">
            📱 Tablet
        </button>
        <button class="device-btn" data-device="mobile" title="Mobile">
            📲 Mobile
        </button>
    </div>

    <a href="{{ url()->previous(route('admin.pages.index')) }}" class="preview-close">✕ Close</a>
</div>

{{-- ── Preview canvas ── --}}
<div class="preview-canvas">
    <div class="preview-frame" id="preview-frame">

        {{-- Page header --}}
        <div class="page-header">
            <div class="page-header-eyebrow">Page Preview</div>
            <div class="page-header-title">{{ $page->name }}</div>
            @if($page->description)
                <div class="page-header-desc">{{ $page->description }}</div>
            @endif
            <div class="page-status {{ $page->is_active ? 'active' : 'inactive' }}">
                {{ $page->is_active ? '● Active' : '○ Inactive' }}
            </div>
        </div>

        {{-- Sections --}}
        @if($page->sections->isEmpty())
            <div class="no-sections">
                <div class="no-sections-icon">🗂</div>
                <div class="no-sections-text">No sections have been added to this page yet.</div>
            </div>
        @else
            <div class="sections-list">
                @foreach($page->sections as $section)
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-name">{{ $section->name }}</span>
                        <span class="section-slug-chip">{{ $section->slug }}</span>
                        @if($section->type)
                            <span class="section-type-chip">{{ $section->type }}</span>
                        @endif
                        <span class="section-order-chip">#{{ $section->sort_order }}</span>
                    </div>

                    @if($section->contentBlocks->isEmpty())
                        <div class="no-blocks">No content blocks in this section.</div>
                    @else
                        <div class="blocks-grid">
                            @foreach($section->contentBlocks as $block)
                            <div class="block-card">
                                <div class="block-header">
                                    <span class="block-key">{{ $block->key }}</span>
                                    <span class="block-type-badge {{ $block->type }}">{{ $block->type }}</span>
                                </div>
                                <div class="block-body">
                                    @if($block->type === 'image')
                                        @if($block->url)
                                            <img
                                                src="{{ $block->url }}"
                                                alt="{{ $block->key }}"
                                                class="block-image-preview"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
                                            >
                                            <div class="block-image-url" style="display:none;">
                                                <em>Image failed to load:</em><br>{{ $block->url }}
                                            </div>
                                            <div class="block-image-url">{{ $block->url }}</div>
                                        @elseif($block->content)
                                            <span class="block-content-text">{{ $block->content }}</span>
                                        @else
                                            <span class="block-empty">No image URL set.</span>
                                        @endif

                                    @elseif($block->type === 'link')
                                        @if($block->url)
                                            <a href="{{ $block->url }}" class="block-link-url" target="_blank" rel="noopener">
                                                🔗 {{ $block->content ?: $block->url }}
                                            </a>
                                        @elseif($block->content)
                                            <span class="block-content-text">{{ $block->content }}</span>
                                        @else
                                            <span class="block-empty">No link URL set.</span>
                                        @endif

                                    @elseif($block->type === 'list')
                                        @if($block->content)
                                            <ul class="block-list-items">
                                                @foreach(explode("\n", $block->content) as $item)
                                                    @if(trim($item))
                                                        <li>{{ trim($item) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="block-empty">No list items.</span>
                                        @endif

                                    @else
                                        {{-- text (default) --}}
                                        @if($block->content)
                                            <p class="block-content-text">{{ $block->content }}</p>
                                        @else
                                            <span class="block-empty">No content.</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif

    </div>{{-- /preview-frame --}}
</div>{{-- /preview-canvas --}}

<script>
(function () {
    var frame   = document.getElementById('preview-frame');
    var buttons = document.querySelectorAll('.device-btn');

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            buttons.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');

            var device = btn.dataset.device;
            frame.classList.remove('tablet', 'mobile');
            if (device === 'tablet') frame.classList.add('tablet');
            if (device === 'mobile') frame.classList.add('mobile');
        });
    });
})();
</script>
</body>
</html>
