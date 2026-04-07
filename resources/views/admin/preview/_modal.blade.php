{{--
    Preview Modal — include this in any admin index view that needs page preview.
    Requires: each preview trigger button has:
        class="preview-trigger"
        data-slug="{{ $page->slug }}"
        data-name="{{ $page->name }}"
--}}

<style>
/* ── Preview modal overlay ── */
#preview-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(2,6,23,.75);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 16px;
}
#preview-modal.modal-open { display: flex; }

.preview-modal-wrap {
    width: 100%;
    max-width: 1320px;
    height: 90vh;
    background: #1e293b;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 32px 80px rgba(0,0,0,.6);
    animation: pmSlideIn .18s ease;
}
@keyframes pmSlideIn {
    from { opacity:0; transform: translateY(12px) scale(.98); }
    to   { opacity:1; transform: translateY(0)   scale(1); }
}

/* Header */
.preview-modal-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    border-bottom: 1px solid #334155;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.preview-modal-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
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

.preview-modal-title {
    color: #f1f5f9;
    font-size: 14px;
    font-weight: 600;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-modal-url {
    font-size: 12px;
    color: #64748b;
    font-weight: 400;
    margin-left: 4px;
}

/* Device toggles */
.pm-device-toggles {
    display: flex;
    gap: 3px;
    background: #0f172a;
    border-radius: 8px;
    padding: 3px;
    flex-shrink: 0;
}
.pm-device-btn {
    background: none;
    border: none;
    color: #64748b;
    padding: 5px 11px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    transition: background .14s, color .14s;
    white-space: nowrap;
}
.pm-device-btn:hover { background: #1e293b; color: #cbd5e1; }
.pm-device-btn.active { background: #3b82f6; color: #fff; }

/* Open in tab link */
.pm-open-tab {
    font-size: 12px;
    color: #64748b;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 6px;
    transition: color .14s;
    white-space: nowrap;
    flex-shrink: 0;
}
.pm-open-tab:hover { color: #94a3b8; }

/* Close button */
.preview-modal-close {
    background: none;
    border: 1px solid #334155;
    color: #94a3b8;
    padding: 5px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: border-color .14s, color .14s;
    flex-shrink: 0;
    white-space: nowrap;
}
.preview-modal-close:hover { border-color: #64748b; color: #e2e8f0; }

/* Body + iframe container */
.preview-modal-body {
    flex: 1;
    background: #94a3b8;
    display: flex;
    justify-content: center;
    overflow: hidden;
    padding: 0;
    transition: background .2s;
}

.preview-iframe-wrap {
    width: 100%;
    max-width: 100%;
    transition: max-width .25s ease;
    display: flex;
    flex-direction: column;
    position: relative;
}
.preview-iframe-wrap.tablet { max-width: 768px; }
.preview-iframe-wrap.mobile { max-width: 390px; }

#preview-iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
    background: #fff;
}

/* Loading spinner shown while iframe loads */
.preview-loading {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #fff;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
    pointer-events: none;
}
.preview-spinner {
    width: 28px;
    height: 28px;
    border: 3px solid #e2e8f0;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

{{-- ── Modal markup ── --}}
<div id="preview-modal" role="dialog" aria-modal="true" aria-labelledby="preview-modal-title">
    <div class="preview-modal-wrap">

        {{-- Header --}}
        <div class="preview-modal-header">
            <span class="preview-modal-badge">👁 Preview Mode</span>
            <span class="preview-modal-title" id="preview-modal-title">—</span>

            <div class="pm-device-toggles" role="group" aria-label="Device preview">
                <button class="pm-device-btn active" data-device="desktop">🖥 Desktop</button>
                <button class="pm-device-btn"        data-device="tablet">📱 Tablet</button>
                <button class="pm-device-btn"        data-device="mobile">📲 Mobile</button>
            </div>

            <a id="preview-open-tab" href="#" target="_blank" rel="noopener" class="pm-open-tab" title="Open in new tab">↗ Open tab</a>

            <button class="preview-modal-close" id="preview-modal-close">✕ Close</button>
        </div>

        {{-- iFrame body --}}
        <div class="preview-modal-body">
            <div class="preview-iframe-wrap" id="preview-iframe-wrap">
                <div class="preview-loading" id="preview-loading">
                    <div class="preview-spinner"></div>
                    <span>Loading preview…</span>
                </div>
                <iframe
                    id="preview-iframe"
                    src=""
                    title="Page Preview"
                    sandbox="allow-same-origin allow-scripts allow-forms allow-popups"
                ></iframe>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
(function () {
'use strict';

/* ── Slug → frontend URL ── */
function slugToUrl(slug) {
    if (!slug || slug === 'home') return '/';
    return '/' + slug;
}

var modal       = document.getElementById('preview-modal');
var titleEl     = document.getElementById('preview-modal-title');
var iframeWrap  = document.getElementById('preview-iframe-wrap');
var iframe      = document.getElementById('preview-iframe');
var loading     = document.getElementById('preview-loading');
var openTabLink = document.getElementById('preview-open-tab');
var closeBtn    = document.getElementById('preview-modal-close');
var deviceBtns  = document.querySelectorAll('.pm-device-btn');

/* Open preview */
function openPreview(slug, name) {
    var url = slugToUrl(slug);

    titleEl.textContent = name + ' ';
    var urlSpan = document.createElement('span');
    urlSpan.className = 'preview-modal-url';
    urlSpan.textContent = url;
    titleEl.appendChild(urlSpan);

    openTabLink.href = url;

    /* Reset device to desktop */
    deviceBtns.forEach(function (b) { b.classList.remove('active'); });
    deviceBtns[0].classList.add('active');
    iframeWrap.className = 'preview-iframe-wrap';

    /* Show loading indicator, then set src */
    loading.style.display = 'flex';
    iframe.src = '';
    requestAnimationFrame(function () {
        iframe.src = url;
    });

    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
}

/* Hide loading once iframe content is ready */
iframe.addEventListener('load', function () {
    loading.style.display = 'none';
});

/* Close preview */
function closePreview() {
    modal.classList.remove('modal-open');
    document.body.style.overflow = '';
    iframe.src = '';
    loading.style.display = 'flex';
}

closeBtn.addEventListener('click', closePreview);
modal.addEventListener('click', function (e) {
    if (e.target === modal) closePreview();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('modal-open')) closePreview();
});

/* Device toggles */
deviceBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
        deviceBtns.forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        iframeWrap.classList.remove('tablet', 'mobile');
        if (btn.dataset.device === 'tablet') iframeWrap.classList.add('tablet');
        if (btn.dataset.device === 'mobile') iframeWrap.classList.add('mobile');
    });
});

/* Wire up all preview trigger buttons on the page */
document.querySelectorAll('.preview-trigger').forEach(function (btn) {
    btn.addEventListener('click', function () {
        openPreview(btn.dataset.slug, btn.dataset.name);
    });
});

})();
</script>
@endpush
