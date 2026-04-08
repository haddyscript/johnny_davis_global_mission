<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

@php
    $authUser    = auth()->user();
    $userName    = $authUser?->name ?? 'Admin';
    $userEmail   = $authUser?->email ?? '';
    $userAvatar  = $authUser?->avatar ? asset('storage/' . $authUser->avatar) : null;
    $initials    = collect(explode(' ', $userName))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');

    // Route-aware search target
    $searchAction = route('admin.pages.index');
    if (request()->routeIs('admin.sections.*'))       $searchAction = route('admin.sections.index');
    if (request()->routeIs('admin.content-blocks.*'))  $searchAction = route('admin.content-blocks.index');
    if (request()->routeIs('admin.email-templates.*')) $searchAction = route('admin.email-templates.index');
    if (request()->routeIs('admin.donations.*'))      $searchAction = route('admin.donations.index');
    $searchValue  = request('search', '');
@endphp

<div class="admin-shell">

    {{-- ── Sidebar ── --}}
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
            <a href="{{ route('admin.pages.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">📄</div>
                <div class="admin-nav-label">Pages</div>
            </a>
            <a href="{{ route('admin.sections.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">✍️</div>
                <div class="admin-nav-label">Sections</div>
            </a>
            <a href="{{ route('admin.content-blocks.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.content-blocks.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">🧩</div>
                <div class="admin-nav-label">Content Blocks</div>
            </a>

            <div class="admin-nav-section" style="margin-top:8px;">Email</div>
            <a href="{{ route('admin.email-templates.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">📨</div>
                <div class="admin-nav-label">Email Templates</div>
            </a>

            <div class="admin-nav-section" style="margin-top:8px;">Fundraising</div>
            <a href="{{ route('admin.donations.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">💰</div>
                <div class="admin-nav-label">Donations</div>
                @php $pendingDonations = \App\Models\Donation::where('status', 'pending')->count(); @endphp
                @if($pendingDonations > 0)
                    <div class="admin-nav-badge">{{ $pendingDonations }}</div>
                @endif
            </a>

            <div class="admin-nav-section" style="margin-top:8px;">Inbox</div>
            <a href="{{ route('admin.contact-messages.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">✉️</div>
                <div class="admin-nav-label">Messages</div>
                @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                @if($unread > 0)
                    <div class="admin-nav-badge">{{ $unread }}</div>
                @endif
            </a>
            <a href="{{ route('admin.subscribers.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">📧</div>
                <div class="admin-nav-label">Subscribers</div>
            </a>
        </nav>

        {{-- Sidebar user footer --}}
        <div class="admin-footer-nav">
            <div class="sidebar-user">
                @if($userAvatar)
                    <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="sidebar-user-avatar-img">
                @else
                    <div class="sidebar-user-avatar">{{ $initials }}</div>
                @endif
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ $userName }}</div>
                    <div class="sidebar-user-role">Super Admin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn" title="Sign out">⏻</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main content ── --}}
    <main class="admin-main">

        {{-- Topbar --}}
        <div class="admin-topbar">
            <div class="topbar-left">
                <div class="admin-page-title">@yield('page-title')</div>
                <div class="topbar-breadcrumb">
                    <a href="{{ route('admin.pages.index') }}" class="topbar-breadcrumb-link">Admin</a>
                    <span class="topbar-breadcrumb-sep">›</span>
                    <span>@yield('page-title', 'Dashboard')</span>
                </div>
            </div>

            <div class="admin-topbar-right">

                {{-- Global search --}}
                <form method="GET" action="{{ $searchAction }}" class="topbar-search-form" id="topbar-search-form">
                    <div class="topbar-search-wrap">
                        <span class="topbar-search-icon">🔍</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $searchValue }}"
                            class="topbar-search-input"
                            id="topbar-search"
                            placeholder="Search…"
                            autocomplete="off"
                        >
                        <kbd class="topbar-search-kbd" id="topbar-kbd">/</kbd>
                    </div>
                </form>

                {{-- Notifications --}}
                <div class="topbar-dropdown-wrap" id="notif-wrap">
                    <button class="admin-icon-btn topbar-icon-btn" id="notif-btn" aria-label="Notifications" aria-expanded="false">
                        <span>🔔</span>
                        <span class="notif-dot"></span>
                    </button>
                    <div class="topbar-dropdown notif-dropdown" id="notif-dropdown" role="menu">
                        <div class="dropdown-header">
                            <span class="dropdown-header-title">Notifications</span>
                            <span class="dropdown-header-badge">3</span>
                        </div>
                        <div class="notif-list">
                            <div class="notif-item notif-unread">
                                <div class="notif-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">📄</div>
                                <div class="notif-body">
                                    <div class="notif-text">New page created successfully</div>
                                    <div class="notif-time">Just now</div>
                                </div>
                                <span class="notif-indicator"></span>
                            </div>
                            <div class="notif-item notif-unread">
                                <div class="notif-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">✍️</div>
                                <div class="notif-body">
                                    <div class="notif-text">3 sections updated</div>
                                    <div class="notif-time">2 min ago</div>
                                </div>
                                <span class="notif-indicator"></span>
                            </div>
                            <div class="notif-item">
                                <div class="notif-icon" style="background:rgba(245,158,11,0.12);color:#d97706;">🧩</div>
                                <div class="notif-body">
                                    <div class="notif-text">Content block sync complete</div>
                                    <div class="notif-time">1 hr ago</div>
                                </div>
                            </div>
                            <div class="notif-item">
                                <div class="notif-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
                                <div class="notif-body">
                                    <div class="notif-text">Website content published</div>
                                    <div class="notif-time">Yesterday</div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <button class="dropdown-footer-btn" id="mark-read-btn">Mark all as read</button>
                        </div>
                    </div>
                </div>

                {{-- Profile / User menu --}}
                <div class="topbar-dropdown-wrap" id="profile-wrap">
                    <button class="admin-user-chip topbar-user-btn" id="profile-btn" aria-expanded="false">
                        @if($userAvatar)
                            <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="topbar-user-avatar-img">
                        @else
                            <div class="admin-user-avatar">{{ $initials }}</div>
                        @endif
                        <div class="topbar-user-info">
                            <div class="admin-user-name">{{ $userName }}</div>
                            <div class="topbar-user-role">Content Admin</div>
                        </div>
                        <span class="topbar-chevron" id="topbar-chevron">▾</span>
                    </button>

                    <div class="topbar-dropdown profile-dropdown" id="profile-dropdown" role="menu">
                        {{-- User header --}}
                        <div class="profile-dropdown-header">
                            @if($userAvatar)
                                <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="profile-dropdown-avatar" style="object-fit:cover;width:38px;height:38px;border-radius:12px;">
                            @else
                                <div class="profile-dropdown-avatar">{{ $initials }}</div>
                            @endif
                            <div>
                                <div class="profile-dropdown-name">{{ $userName }}</div>
                                <div class="profile-dropdown-email">{{ $userEmail }}</div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        {{-- Menu items --}}
                        <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                            <span class="dropdown-item-icon">👤</span>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="dropdown-item" role="menuitem">
                            <span class="dropdown-item-icon">🏠</span>
                            <span>Admin Home</span>
                        </a>
                        <a href="{{ url('/') }}" class="dropdown-item" role="menuitem" target="_blank">
                            <span class="dropdown-item-icon">🌐</span>
                            <span>View Website</span>
                            <span class="dropdown-item-ext">↗</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-danger" role="menuitem">
                                <span class="dropdown-item-icon">⏻</span>
                                <span>Sign out</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>{{-- /topbar-right --}}
        </div>{{-- /topbar --}}

        @yield('content')

    </main>
</div>{{-- /admin-shell --}}

{{-- Global toast --}}
<div id="global-toast-container" style="position:fixed;bottom:24px;right:24px;z-index:1100;display:flex;flex-direction:column;gap:10px;pointer-events:none;"></div>

<script>
(function(){
    /* ── Toast ── */
    function showToast(msg, type){
        var c = document.getElementById('global-toast-container');
        if(!c) return;
        var t = document.createElement('div');
        t.className = 'toast toast-'+(type||'success');
        t.style.pointerEvents = 'all';
        t.innerHTML = '<span class="toast-icon">'+(type==='error'?'❌':'✅')+'</span><span>'+msg+'</span>'
            +'<button class="toast-close" style="margin-left:auto;background:none;border:none;cursor:pointer;opacity:.6;font-size:12px;padding:2px 4px;">✕</button>';
        c.appendChild(t);
        requestAnimationFrame(function(){ t.classList.add('toast-in'); });
        function dismiss(){
            t.classList.remove('toast-in'); t.classList.add('toast-out');
            t.addEventListener('transitionend', function(){ t.remove(); }, {once:true});
        }
        t.querySelector('.toast-close').addEventListener('click', dismiss);
        setTimeout(dismiss, 4500);
    }
    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error'))   showToast(@json(session('error')),   'error');   @endif
    window.showAdminToast = showToast;

    /* ── Search keyboard shortcut "/" ── */
    var searchInput = document.getElementById('topbar-search');
    var searchKbd   = document.getElementById('topbar-kbd');
    if (searchInput) {
        document.addEventListener('keydown', function(e){
            if (e.key === '/' && document.activeElement !== searchInput
                && document.activeElement.tagName !== 'INPUT'
                && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
            if (e.key === 'Escape' && document.activeElement === searchInput) {
                searchInput.blur();
            }
        });
        searchInput.addEventListener('focus', function(){ searchKbd.style.display = 'none'; });
        searchInput.addEventListener('blur',  function(){ searchKbd.style.display = ''; });
    }

    /* ── Generic dropdown helper ── */
    function initDropdown(btnId, dropdownId, wrapId){
        var btn      = document.getElementById(btnId);
        var dropdown = document.getElementById(dropdownId);
        var wrap     = document.getElementById(wrapId);
        if (!btn || !dropdown) return;

        btn.addEventListener('click', function(e){
            e.stopPropagation();
            var isOpen = dropdown.classList.contains('dropdown-open');
            closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('dropdown-open');
                btn.setAttribute('aria-expanded', 'true');
                btn.classList.add('btn-active');
            }
        });

        return { btn: btn, dropdown: dropdown };
    }

    function closeAllDropdowns(){
        document.querySelectorAll('.topbar-dropdown').forEach(function(d){
            d.classList.remove('dropdown-open');
        });
        document.querySelectorAll('[aria-expanded]').forEach(function(b){
            b.setAttribute('aria-expanded', 'false');
            b.classList.remove('btn-active');
        });
        var chevron = document.getElementById('topbar-chevron');
        if (chevron) chevron.style.transform = '';
    }

    initDropdown('notif-btn', 'notif-dropdown', 'notif-wrap');
    initDropdown('profile-btn', 'profile-dropdown', 'profile-wrap');

    /* Chevron animation on profile toggle */
    var profileBtn = document.getElementById('profile-btn');
    var chevron    = document.getElementById('topbar-chevron');
    if (profileBtn && chevron) {
        profileBtn.addEventListener('click', function(){
            var open = document.getElementById('profile-dropdown').classList.contains('dropdown-open');
            chevron.style.transform = open ? 'rotate(180deg)' : '';
        });
    }

    /* Close on outside click */
    document.addEventListener('click', function(e){
        if (!e.target.closest('.topbar-dropdown-wrap')) closeAllDropdowns();
    });

    /* Close on Escape */
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeAllDropdowns();
    });

    /* Mark all notifications as read */
    var markReadBtn = document.getElementById('mark-read-btn');
    if (markReadBtn) {
        markReadBtn.addEventListener('click', function(){
            document.querySelectorAll('.notif-unread').forEach(function(n){
                n.classList.remove('notif-unread');
                var ind = n.querySelector('.notif-indicator');
                if (ind) ind.remove();
            });
            var badge = document.querySelector('.dropdown-header-badge');
            if (badge) { badge.textContent = '0'; badge.style.opacity = '0.4'; }
            var dot = document.querySelector('.notif-dot');
            if (dot) dot.style.display = 'none';
            markReadBtn.textContent = 'All caught up ✓';
            markReadBtn.disabled = true;
        });
    }
})();
</script>

@stack('scripts')
</body>
</html>
