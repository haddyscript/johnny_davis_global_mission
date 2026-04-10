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

            {{-- MAIN --}}
            <div class="admin-nav-section">Main</div>
            <a href="{{ route('admin.dashboard') }}"
               class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="admin-nav-icon">🏠</div>
                <div class="admin-nav-label">Dashboard</div>
            </a>

            {{-- FUNDRAISING --}}
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
            <span class="admin-nav-item admin-nav-item-soon">
                <div class="admin-nav-icon">🎯</div>
                <div class="admin-nav-label">Campaigns</div>
                <span class="admin-nav-soon-tag">Soon</span>
            </span>
            <span class="admin-nav-item admin-nav-item-soon">
                <div class="admin-nav-icon">👥</div>
                <div class="admin-nav-label">Donors</div>
                <span class="admin-nav-soon-tag">Soon</span>
            </span>

            {{-- CONTENT --}}
            <div class="admin-nav-section" style="margin-top:8px;">Content</div>
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
            <a href="{{ route('admin.email-templates.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">📨</div>
                <div class="admin-nav-label">Email Templates</div>
            </a>
            <a href="{{ route('admin.email-logs.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.email-logs.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">📋</div>
                <div class="admin-nav-label">Email Logs</div>
            </a>

            {{-- COMMUNITY --}}
            <div class="admin-nav-section" style="margin-top:8px;">Community</div>
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
            <span class="admin-nav-item admin-nav-item-soon">
                <div class="admin-nav-icon">🙌</div>
                <div class="admin-nav-label">Volunteers</div>
                <span class="admin-nav-soon-tag">Soon</span>
            </span>
            <span class="admin-nav-item admin-nav-item-soon">
                <div class="admin-nav-icon">📅</div>
                <div class="admin-nav-label">Events</div>
                <span class="admin-nav-soon-tag">Soon</span>
            </span>

            {{-- SITE --}}
            <div class="admin-nav-section" style="margin-top:8px;">Site</div>
            <a href="{{ route('admin.nav-items.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.nav-items.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">🔗</div>
                <div class="admin-nav-label">Navigation</div>
            </a>

            {{-- SYSTEM --}}
            <div class="admin-nav-section" style="margin-top:8px;">System</div>
            <a href="{{ route('admin.notifications.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">🔔</div>
                <div class="admin-nav-label">Notifications</div>
                @php $unreadNotifs = \App\Models\AdminNotification::where('is_read', false)->count(); @endphp
                @if($unreadNotifs > 0)
                    <div class="admin-nav-badge">{{ $unreadNotifs > 99 ? '99+' : $unreadNotifs }}</div>
                @endif
            </a>
            <a href="{{ route('profile.edit') }}"
               class="admin-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <div class="admin-nav-icon">👤</div>
                <div class="admin-nav-label">Profile</div>
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
                    <a href="{{ route('admin.dashboard') }}" class="topbar-breadcrumb-link">Admin</a>
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
                        <span class="notif-badge" id="notif-badge" style="display:none;">0</span>
                    </button>
                    <div class="topbar-dropdown notif-dropdown" id="notif-dropdown" role="menu">
                        <div class="dropdown-header">
                            <span class="dropdown-header-title">Notifications</span>
                            <span class="dropdown-header-badge" id="notif-header-badge" style="display:none;">0</span>
                        </div>
                        <div class="notif-list" id="notif-list">
                            <div id="notif-loading" style="padding:28px 0;text-align:center;color:var(--text-muted);">
                                <div class="notif-spinner"></div>
                            </div>
                        </div>
                        <div class="dropdown-footer" style="display:flex;flex-direction:column;gap:8px;">
                            <button class="dropdown-footer-btn" id="mark-all-read-btn">Mark all as read</button>
                            <a href="{{ route('admin.notifications.index') }}" class="dropdown-footer-btn" style="text-align:center;text-decoration:none;display:block;">View all notifications →</a>
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

        <div class="admin-content">
            @yield('content')
        </div>

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

    /* ── Notification system ── */
    var NOTIF_RECENT_URL    = '{{ route("admin.notifications.recent") }}';
    var NOTIF_MARK_READ_URL = '{{ url("admin/notifications") }}';
    var NOTIF_MARK_ALL_URL  = '{{ route("admin.notifications.mark-all-read") }}';
    var NOTIF_COUNT_URL     = '{{ route("admin.notifications.unread-count") }}';
    var CSRF_TOKEN          = '{{ csrf_token() }}';

    var notifList        = document.getElementById('notif-list');
    var notifLoading     = document.getElementById('notif-loading');
    var notifBadge       = document.getElementById('notif-badge');
    var notifHeaderBadge = document.getElementById('notif-header-badge');
    var markAllBtn       = document.getElementById('mark-all-read-btn');
    var notifBtn         = document.getElementById('notif-btn');
    var notifDropdown    = document.getElementById('notif-dropdown');
    var notifLoaded      = false;

    function updateBadge(count) {
        if (count > 0) {
            notifBadge.textContent = count > 99 ? '99+' : count;
            notifBadge.style.display = '';
            notifHeaderBadge.textContent = count > 99 ? '99+' : count;
            notifHeaderBadge.style.display = '';
        } else {
            notifBadge.style.display = 'none';
            notifHeaderBadge.style.display = 'none';
        }
        if (markAllBtn) markAllBtn.disabled = (count === 0);
    }

    function renderNotif(n) {
        var item = document.createElement('div');
        item.className = 'notif-item' + (n.is_read ? '' : ' notif-unread');
        item.dataset.id = n.id;
        if (n.action_url) item.style.cursor = 'pointer';

        var titleLine = '<div class="notif-text">' + escHtml(n.title) + '</div>';
        var msgLine   = '<div class="notif-msg">'  + escHtml(n.message) + '</div>';
        var timeLine  = '<div class="notif-time">'  + escHtml(n.time_ago) + '</div>';
        var indicator = n.is_read ? '' : '<span class="notif-indicator"></span>';

        item.innerHTML =
            '<div class="notif-icon" style="background:' + escHtml(n.icon_bg) + ';color:' + escHtml(n.icon_color) + ';">' + n.icon + '</div>'
            + '<div class="notif-body">' + titleLine + msgLine + timeLine + '</div>'
            + indicator;

        item.addEventListener('click', function() {
            if (!n.is_read) {
                fetch(NOTIF_MARK_READ_URL + '/' + n.id + '/read', {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                }).then(function() {
                    item.classList.remove('notif-unread');
                    var ind = item.querySelector('.notif-indicator');
                    if (ind) ind.remove();
                    n.is_read = true;
                    var current = parseInt(notifBadge.textContent) || 0;
                    updateBadge(Math.max(0, current - 1));
                });
            }
            if (n.action_url) {
                closeAllDropdowns();
                window.location.href = n.action_url;
            }
        });

        return item;
    }

    function escHtml(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function loadNotifications() {
        notifLoading.style.display = 'block';
        fetch(NOTIF_RECENT_URL, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                notifLoading.style.display = 'none';
                updateBadge(data.unread_count);

                /* Remove old rendered items (keep the loading div) */
                notifList.querySelectorAll('.notif-item, .notif-empty').forEach(function(el) { el.remove(); });

                if (data.notifications.length === 0) {
                    var empty = document.createElement('div');
                    empty.className = 'notif-empty';
                    empty.innerHTML = '<span style="font-size:22px;">🔕</span><div>No notifications yet</div>';
                    notifList.appendChild(empty);
                } else {
                    data.notifications.forEach(function(n) {
                        notifList.appendChild(renderNotif(n));
                    });
                }
                notifLoaded = true;
            })
            .catch(function() { notifLoading.style.display = 'none'; });
    }

    /* Load on first open; reload on subsequent opens to stay fresh */
    notifBtn.addEventListener('click', function() {
        if (notifDropdown.classList.contains('dropdown-open') || !notifLoaded) {
            loadNotifications();
        }
    });

    /* Mark all as read */
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch(NOTIF_MARK_ALL_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            }).then(function() {
                notifList.querySelectorAll('.notif-item').forEach(function(item) {
                    item.classList.remove('notif-unread');
                    var ind = item.querySelector('.notif-indicator');
                    if (ind) ind.remove();
                });
                updateBadge(0);
                if (window.showAdminToast) window.showAdminToast('All notifications marked as read.', 'success');
            });
        });
    }

    /* Poll for unread count every 30 seconds */
    function pollUnreadCount() {
        fetch(NOTIF_COUNT_URL, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                updateBadge(data.unread_count);
                /* If dropdown is open and count increased, reload list */
                if (notifDropdown.classList.contains('dropdown-open')) {
                    loadNotifications();
                } else {
                    notifLoaded = false; /* force reload on next open */
                }
            })
            .catch(function() {});
    }

    /* Initial badge count on page load */
    fetch(NOTIF_COUNT_URL, { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(data) { updateBadge(data.unread_count); })
        .catch(function() {});

    setInterval(pollUnreadCount, 30000);

})();
</script>

@stack('scripts')
</body>
</html>
