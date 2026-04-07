<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-mark">✦</div>
                <div>
                    <div class="admin-logo-text">JDGM Admin</div>
                    <div class="admin-logo-sub">Content CMS</div>
                </div>
            </div>

            <nav class="admin-nav">
                <div class="admin-nav-section">Content</div>
                <a href="{{ route('admin.pages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <div class="admin-nav-icon">📄</div>
                    <div class="admin-nav-label">Pages</div>
                </a>
                <a href="{{ route('admin.sections.index') }}" class="admin-nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                    <div class="admin-nav-icon">✍️</div>
                    <div class="admin-nav-label">Sections</div>
                </a>
                <a href="{{ route('admin.content-blocks.index') }}" class="admin-nav-item {{ request()->routeIs('admin.content-blocks.*') ? 'active' : '' }}">
                    <div class="admin-nav-icon">🧩</div>
                    <div class="admin-nav-label">Content Blocks</div>
                </a>
            </nav>

            <div class="admin-footer-nav">
                <div class="admin-nav-item">
                    <div style="width:36px;height:36px;border-radius:14px;background:rgba(20,184,166,0.18);display:grid;place-items:center;font-weight:700;color:#0f766e;">JD</div>
                    <div style="flex:1;">
                        <div class="admin-nav-label" style="color:rgba(255,255,255,0.85);font-size:13px;">Johnny Davis</div>
                        <div style="font-size:11px;color:rgba(255,255,255,0.45);">Super Admin</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar">
                <div>
                    <div class="admin-page-title">@yield('page-title')</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">Manage your website content in one place.</div>
                </div>
                <div class="admin-topbar-right">
                    <input class="admin-search" placeholder="Search pages, sections, blocks..." />
                    <div class="admin-icon-btn" title="Notifications">🔔<div class="notif-dot"></div></div>
                    <div class="admin-icon-btn" title="Help">❓</div>
                    <div class="admin-user-chip">
                        <div class="admin-user-avatar">JD</div>
                        <div>
                            <div class="admin-user-name">Johnny Davis</div>
                            <div style="font-size:11px;color:var(--text-muted);">Content Admin</div>
                        </div>
                    </div>
                </div>
            </div>

                    @yield('content')
        </main>
    </div>

    {{-- Global toast container & flash handler --}}
    <div id="global-toast-container" style="position:fixed;bottom:24px;right:24px;z-index:1100;display:flex;flex-direction:column;gap:10px;pointer-events:none;"></div>

    <script>
    (function(){
        function showToast(msg, type){
            var c = document.getElementById('global-toast-container');
            if(!c) return;
            var t = document.createElement('div');
            t.className = 'toast toast-'+(type||'success');
            t.style.pointerEvents = 'all';
            t.innerHTML = '<span class="toast-icon">'+(type==='error'?'❌':'✅')+'</span><span>'+msg+'</span><button class="toast-close" style="margin-left:auto;background:none;border:none;cursor:pointer;opacity:.6;font-size:12px;padding:2px 4px;">✕</button>';
            c.appendChild(t);
            requestAnimationFrame(function(){ t.classList.add('toast-in'); });
            function dismiss(){
                t.classList.remove('toast-in');
                t.classList.add('toast-out');
                t.addEventListener('transitionend', function(){ t.remove(); }, {once:true});
            }
            t.querySelector('.toast-close').addEventListener('click', dismiss);
            setTimeout(dismiss, 4500);
        }
        @if(session('success')) showToast(@json(session('success')), 'success'); @endif
        @if(session('error'))   showToast(@json(session('error')),   'error');   @endif
        window.showAdminToast = showToast;
    })();
    </script>

    @stack('scripts')
</body>
</html>