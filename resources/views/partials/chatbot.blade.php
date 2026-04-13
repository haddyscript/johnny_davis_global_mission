{{-- ══════════════════════════════════════════════════════
     AI CHATBOT WIDGET — Johnny Davis Global Missions
══════════════════════════════════════════════════════ --}}

{{-- ── Floating toggle button ── --}}
<button id="jdgm-chat-toggle" aria-label="Open chat assistant" title="Ask us anything">
    {{-- Ripple ring --}}
    <span class="jdgm-toggle-ring"></span>
    {{-- Icon states --}}
    <span id="jdgm-chat-icon-open" class="jdgm-toggle-icon">
        {{-- Chat bubble with sparkle --}}
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            <path d="M9 10h.01M12 10h.01M15 10h.01" stroke-width="2.5"/>
        </svg>
    </span>
    <span id="jdgm-chat-icon-close" class="jdgm-toggle-icon" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </span>
    {{-- Notification badge --}}
    <span id="jdgm-chat-badge" aria-hidden="true"></span>
    {{-- Tooltip --}}
    <span class="jdgm-toggle-tooltip">Ask JDGM Assistant</span>
</button>

{{-- ── Chat window ── --}}
<div id="jdgm-chat-window" role="dialog" aria-modal="true" aria-label="JDGM Chat Assistant">

    {{-- Header --}}
    <div id="jdgm-chat-header">
        <div class="jdgm-header-left">
            <div id="jdgm-chat-avatar" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                    <circle cx="9" cy="9.5" r="1" fill="currentColor" stroke="none"/>
                    <circle cx="15" cy="9.5" r="1" fill="currentColor" stroke="none"/>
                </svg>
            </div>
            <div>
                <div class="jdgm-header-name">JDGM Assistant
                    <span class="jdgm-header-badge">AI</span>
                </div>
                <div class="jdgm-header-status">
                    <span id="jdgm-status-dot"></span>
                    <span id="jdgm-status-text">Online</span>
                </div>
            </div>
        </div>
        <div class="jdgm-header-actions">
            <button id="jdgm-clear-btn" class="jdgm-header-btn" title="Clear conversation" aria-label="Clear conversation">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </button>
            <button id="jdgm-chat-close" class="jdgm-header-btn" aria-label="Close chat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Messages area --}}
    <div id="jdgm-chat-messages" aria-live="polite" aria-relevant="additions"></div>

    {{-- Suggested prompts (shown only before first message) --}}
    <div id="jdgm-chat-suggestions">
        <p class="jdgm-sugg-label">Quick questions</p>
        <div class="jdgm-sugg-grid">
            <button class="jdgm-suggestion" data-q="How can I donate to JDGM?">
                <span class="jdgm-sugg-icon">💝</span> How to donate?
            </button>
            <button class="jdgm-suggestion" data-q="What does $7.99 per month fund?">
                <span class="jdgm-sugg-icon">🍽️</span> $7.99/month?
            </button>
            <button class="jdgm-suggestion" data-q="Where does Johnny Davis Global Missions serve?">
                <span class="jdgm-sugg-icon">🌍</span> Where you serve?
            </button>
            <button class="jdgm-suggestion" data-q="How can I volunteer with JDGM?">
                <span class="jdgm-sugg-icon">🙋</span> Volunteer?
            </button>
            <button class="jdgm-suggestion" data-q="Tell me about Pastor Johnny Davis">
                <span class="jdgm-sugg-icon">✝️</span> About Pastor Johnny
            </button>
            <button class="jdgm-suggestion" data-q="What is the Feed Filipino Children campaign?">
                <span class="jdgm-sugg-icon">🇵🇭</span> Filipino Children
            </button>
        </div>
    </div>

    {{-- Input bar --}}
    <div id="jdgm-chat-input-bar">
        <div class="jdgm-input-wrap">
            <textarea
                id="jdgm-chat-input"
                placeholder="Ask me anything about JDGM…"
                rows="1"
                maxlength="1000"
                aria-label="Type your message"
            ></textarea>
            <span id="jdgm-char-count"></span>
        </div>
        <button id="jdgm-chat-send" aria-label="Send message" disabled>
            <svg id="jdgm-send-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            <svg id="jdgm-stop-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                <rect x="4" y="4" width="16" height="16" rx="3"/>
            </svg>
        </button>
    </div>

    <div id="jdgm-chat-footer">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
        Powered by Gemini AI &nbsp;·&nbsp; JDGM
    </div>
</div>

{{-- ══════════ STYLES ══════════ --}}
<style>
/* ─────────────────────────────────────────
   Root tokens (scoped to chatbot elements)
───────────────────────────────────────── */
:root {
    --jdgm-brand:     #0f766e;
    --jdgm-brand2:    #14b8a6;
    --jdgm-dark:      #0f172a;
    --jdgm-navy:      #0d2137;
    --jdgm-border:    #e2e8f0;
    --jdgm-surface:   #f8fafc;
    --jdgm-muted:     #64748b;
    --jdgm-radius:    18px;
    --jdgm-font:      Inter, 'Segoe UI', Arial, sans-serif;
}

/* ─────────────────────────────────────────
   Floating toggle button
───────────────────────────────────────── */
#jdgm-chat-toggle {
    position: fixed;
    bottom: 90px;
    right: 24px;
    z-index: 9998;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(145deg, #0f766e 0%, #14b8a6 60%, #0ea5e9 100%);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 4px 20px rgba(15, 118, 110, 0.5),
        0 1px 4px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.2);
    transition: transform 0.22s cubic-bezier(.34,1.56,.64,1), box-shadow 0.22s ease;
    font-family: var(--jdgm-font);
}
#jdgm-chat-toggle:hover {
    transform: scale(1.1) translateY(-2px);
    box-shadow:
        0 8px 32px rgba(15, 118, 110, 0.6),
        0 2px 8px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.2);
}
#jdgm-chat-toggle:active {
    transform: scale(0.94);
    transition-duration: 0.1s;
}

/* Pulsing ring */
.jdgm-toggle-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid rgba(20, 184, 166, 0.5);
    animation: jdgm-ring 2.4s ease-out infinite;
    pointer-events: none;
}
@keyframes jdgm-ring {
    0%   { transform: scale(1);    opacity: 0.7; }
    70%  { transform: scale(1.35); opacity: 0; }
    100% { transform: scale(1.35); opacity: 0; }
}

.jdgm-toggle-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.22s cubic-bezier(.34,1.56,.64,1), opacity 0.18s ease;
}

/* Notification badge */
#jdgm-chat-badge {
    display: none;
    position: absolute;
    top: 2px;
    right: 2px;
    width: 18px;
    height: 18px;
    background: #ef4444;
    border-radius: 50%;
    border: 2.5px solid #fff;
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    line-height: 13px;
    text-align: center;
    font-family: var(--jdgm-font);
    animation: jdgm-badge-pop 0.3s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes jdgm-badge-pop {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}

/* Tooltip */
.jdgm-toggle-tooltip {
    position: absolute;
    right: 72px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--jdgm-dark);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease, transform 0.2s ease;
    transform: translateY(-50%) translateX(4px);
    font-family: var(--jdgm-font);
}
.jdgm-toggle-tooltip::after {
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-left-color: var(--jdgm-dark);
}
#jdgm-chat-toggle:hover .jdgm-toggle-tooltip {
    opacity: 1;
    transform: translateY(-50%) translateX(0);
}

/* ─────────────────────────────────────────
   Chat window
───────────────────────────────────────── */
#jdgm-chat-window {
    position: fixed;
    bottom: 164px;
    right: 24px;
    z-index: 9999;
    width: 380px;
    max-width: calc(100vw - 40px);
    border-radius: var(--jdgm-radius);
    background: #fff;
    box-shadow:
        0 24px 80px rgba(15, 23, 42, 0.2),
        0 4px 16px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(15, 23, 42, 0.06);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px) scale(0.94);
    opacity: 0;
    pointer-events: none;
    transition: transform 0.28s cubic-bezier(.34,1.4,.64,1), opacity 0.22s ease;
    max-height: 580px;
    font-family: var(--jdgm-font);
}
#jdgm-chat-window.jdgm-open {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
}

/* ─────────────────────────────────────────
   Header
───────────────────────────────────────── */
#jdgm-chat-header {
    background: linear-gradient(135deg, #0d1f3c 0%, #0f3460 50%, #0f766e 100%);
    color: #fff;
    padding: 14px 16px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}
#jdgm-chat-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='28' fill='none' stroke='rgba(255,255,255,0.04)' stroke-width='1'/%3E%3C/svg%3E") 0 0 / 60px repeat;
    pointer-events: none;
}
.jdgm-header-left {
    display: flex;
    align-items: center;
    gap: 11px;
    position: relative;
}
#jdgm-chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(20,184,166,0.3), rgba(14,165,233,0.3));
    border: 1.5px solid rgba(20, 184, 166, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5eead4;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.jdgm-header-name {
    font-weight: 700;
    font-size: 14px;
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 6px;
}
.jdgm-header-badge {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.06em;
    background: linear-gradient(135deg, #14b8a6, #0ea5e9);
    color: #fff;
    padding: 2px 6px;
    border-radius: 4px;
}
.jdgm-header-status {
    font-size: 11px;
    opacity: 0.8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 2px;
}
#jdgm-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #34d399;
    display: inline-block;
    flex-shrink: 0;
    animation: jdgm-pulse 2.5s ease-in-out infinite;
    box-shadow: 0 0 6px rgba(52, 211, 153, 0.6);
}
@keyframes jdgm-pulse {
    0%, 100% { opacity: 1;   box-shadow: 0 0 6px rgba(52,211,153,0.6); }
    50%       { opacity: 0.5; box-shadow: 0 0 2px rgba(52,211,153,0.2); }
}
.jdgm-header-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    position: relative;
}
.jdgm-header-btn {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.85);
    cursor: pointer;
    border-radius: 8px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
    flex-shrink: 0;
}
.jdgm-header-btn:hover {
    background: rgba(255,255,255,0.2);
    color: #fff;
}

/* ─────────────────────────────────────────
   Messages area
───────────────────────────────────────── */
#jdgm-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 18px 14px 10px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 180px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
    scroll-behavior: smooth;
}
#jdgm-chat-messages::-webkit-scrollbar       { width: 4px; }
#jdgm-chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* Message row wrapper */
.jdgm-msg-row {
    display: flex;
    align-items: flex-end;
    gap: 7px;
    animation: jdgm-msg-in 0.2s cubic-bezier(.34,1.4,.64,1) both;
}
.jdgm-msg-row-user  { flex-direction: row-reverse; }
.jdgm-msg-row-bot   { flex-direction: row; }
@keyframes jdgm-msg-in {
    from { opacity: 0; transform: translateY(10px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0)    scale(1); }
}

/* Bot mini-avatar in messages */
.jdgm-msg-mini-avatar {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    background: linear-gradient(135deg, #0f766e, #14b8a6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 11px;
    font-weight: 700;
}

/* Bubbles */
.jdgm-msg {
    max-width: 82%;
    line-height: 1.6;
    font-size: 13.5px;
    padding: 10px 14px;
    border-radius: 16px;
    word-break: break-word;
    position: relative;
}
.jdgm-msg-user {
    background: linear-gradient(135deg, #0f766e, #14b8a6);
    color: #fff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 12px rgba(15, 118, 110, 0.3);
}
.jdgm-msg-bot {
    background: var(--jdgm-surface);
    color: #1e293b;
    border-bottom-left-radius: 4px;
    border: 1px solid var(--jdgm-border);
}
.jdgm-msg-bot a          { color: var(--jdgm-brand); text-decoration: underline; }
.jdgm-msg-bot strong     { font-weight: 700; color: var(--jdgm-dark); }
.jdgm-msg-bot ul         { margin: 6px 0 0 0; padding-left: 16px; }
.jdgm-msg-bot li         { margin-bottom: 3px; }
.jdgm-msg-bot p          { margin: 0 0 6px; }
.jdgm-msg-bot p:last-child { margin-bottom: 0; }

/* Streaming cursor */
.jdgm-cursor {
    display: inline-block;
    width: 2px;
    height: 1em;
    background: var(--jdgm-brand);
    margin-left: 2px;
    vertical-align: text-bottom;
    border-radius: 1px;
    animation: jdgm-blink 0.8s step-end infinite;
}
@keyframes jdgm-blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0; }
}

/* Timestamp */
.jdgm-msg-time {
    font-size: 10px;
    color: var(--jdgm-muted);
    margin-top: 3px;
    opacity: 0.7;
    align-self: flex-end;
    flex-shrink: 0;
}
.jdgm-msg-row-user .jdgm-msg-time  { text-align: right; }

/* Typing indicator (dots) */
.jdgm-typing {
    padding: 12px 16px;
    background: var(--jdgm-surface);
    border: 1px solid var(--jdgm-border);
    border-radius: 16px;
    border-bottom-left-radius: 4px;
    display: flex;
    gap: 5px;
    align-items: center;
    max-width: 70px;
}
.jdgm-typing span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #94a3b8;
    animation: jdgm-bounce 1.3s ease-in-out infinite;
}
.jdgm-typing span:nth-child(2) { animation-delay: 0.18s; }
.jdgm-typing span:nth-child(3) { animation-delay: 0.36s; }
@keyframes jdgm-bounce {
    0%, 60%, 100% { transform: translateY(0);    background: #94a3b8; }
    30%            { transform: translateY(-6px); background: var(--jdgm-brand2); }
}

/* Welcome card */
.jdgm-welcome {
    background: linear-gradient(135deg, #f0fdf9, #ecfeff);
    border: 1px solid #99f6e4;
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    animation: jdgm-msg-in 0.3s ease both;
}
.jdgm-welcome-emoji { font-size: 28px; margin-bottom: 8px; display: block; }
.jdgm-welcome-title { font-size: 14px; font-weight: 700; color: var(--jdgm-dark); margin-bottom: 5px; }
.jdgm-welcome-body  { font-size: 12.5px; color: var(--jdgm-muted); line-height: 1.6; }

/* ─────────────────────────────────────────
   Suggestion chips
───────────────────────────────────────── */
#jdgm-chat-suggestions {
    padding: 0 12px 12px;
    flex-shrink: 0;
}
.jdgm-sugg-label {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--jdgm-muted);
    margin: 0 0 7px 2px;
}
.jdgm-sugg-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
}
.jdgm-suggestion {
    padding: 7px 10px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 10px;
    border: 1px solid #ccfbf1;
    color: var(--jdgm-brand);
    background: #f0fdf9;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 5px;
    line-height: 1.3;
    font-family: var(--jdgm-font);
}
.jdgm-suggestion:hover {
    background: var(--jdgm-brand);
    color: #fff;
    border-color: var(--jdgm-brand);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(15,118,110,0.25);
}
.jdgm-sugg-icon { font-size: 13px; flex-shrink: 0; }

/* ─────────────────────────────────────────
   Input bar
───────────────────────────────────────── */
#jdgm-chat-input-bar {
    padding: 10px 12px;
    border-top: 1px solid var(--jdgm-border);
    display: flex;
    align-items: flex-end;
    gap: 8px;
    background: #fff;
    flex-shrink: 0;
}
.jdgm-input-wrap {
    flex: 1;
    position: relative;
}
#jdgm-chat-input {
    width: 100%;
    resize: none;
    border: 1.5px solid var(--jdgm-border);
    border-radius: 12px;
    padding: 9px 36px 9px 13px;
    font-size: 13.5px;
    font-family: var(--jdgm-font);
    color: #1e293b;
    background: var(--jdgm-surface);
    outline: none;
    line-height: 1.5;
    max-height: 100px;
    overflow-y: auto;
    transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    box-sizing: border-box;
    display: block;
}
#jdgm-chat-input:focus {
    border-color: var(--jdgm-brand2);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
}
#jdgm-char-count {
    position: absolute;
    bottom: 8px;
    right: 10px;
    font-size: 10px;
    color: #94a3b8;
    pointer-events: none;
    font-family: var(--jdgm-font);
}

/* Send / Stop button */
#jdgm-chat-send {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #0f766e, #14b8a6);
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: opacity 0.15s, transform 0.15s cubic-bezier(.34,1.56,.64,1), box-shadow 0.15s;
    box-shadow: 0 2px 10px rgba(15, 118, 110, 0.35);
}
#jdgm-chat-send:disabled {
    opacity: 0.35;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}
#jdgm-chat-send:not(:disabled):hover {
    transform: scale(1.08) translateY(-1px);
    box-shadow: 0 4px 16px rgba(15, 118, 110, 0.45);
}
#jdgm-chat-send:not(:disabled):active {
    transform: scale(0.92);
    transition-duration: 0.08s;
}

/* ─────────────────────────────────────────
   Footer
───────────────────────────────────────── */
#jdgm-chat-footer {
    padding: 6px 14px 8px;
    font-size: 10.5px;
    color: #b0bec5;
    text-align: center;
    background: #fcfcfe;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-family: var(--jdgm-font);
}

/* ─────────────────────────────────────────
   Mobile
───────────────────────────────────────── */
@media (max-width: 480px) {
    #jdgm-chat-window {
        bottom: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
        border-radius: 20px 20px 0 0;
        max-height: 78vh;
    }
    #jdgm-chat-toggle {
        bottom: 82px;
        right: 16px;
    }
    .jdgm-toggle-tooltip { display: none; }
    .jdgm-sugg-grid { grid-template-columns: 1fr 1fr; }
}
</style>

{{-- ══════════ SCRIPT ══════════ --}}
<script>
(function () {
'use strict';

/* ─── Config ─── */
var STREAM_URL = '{{ route("chatbot.stream") }}';
var CSRF = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';

/* ─── DOM refs ─── */
var toggle    = document.getElementById('jdgm-chat-toggle');
var win       = document.getElementById('jdgm-chat-window');
var closeBtn  = document.getElementById('jdgm-chat-close');
var clearBtn  = document.getElementById('jdgm-clear-btn');
var messages  = document.getElementById('jdgm-chat-messages');
var input     = document.getElementById('jdgm-chat-input');
var sendBtn   = document.getElementById('jdgm-chat-send');
var sendIcon  = document.getElementById('jdgm-send-icon');
var stopIcon  = document.getElementById('jdgm-stop-icon');
var iconOpen  = document.getElementById('jdgm-chat-icon-open');
var iconClose = document.getElementById('jdgm-chat-icon-close');
var badge     = document.getElementById('jdgm-chat-badge');
var statusDot = document.getElementById('jdgm-status-dot');
var statusTxt = document.getElementById('jdgm-status-text');
var suggsEl   = document.getElementById('jdgm-chat-suggestions');
var charCount = document.getElementById('jdgm-char-count');

/* ─── State ─── */
var isOpen     = false;
var isSending  = false;
var hasOpened  = false;
var abortCtrl  = null;   // AbortController for current stream

/* ════════════════════════════════════════
   Open / close
════════════════════════════════════════ */
function openChat() {
    isOpen = true;
    win.classList.add('jdgm-open');
    iconOpen.style.display  = 'none';
    iconClose.style.display = 'flex';
    badge.style.display     = 'none';
    if (!hasOpened) {
        hasOpened = true;
        showWelcome();
    }
    setTimeout(function () { input.focus(); }, 280);
}

function closeChat() {
    isOpen = false;
    win.classList.remove('jdgm-open');
    iconOpen.style.display  = 'flex';
    iconClose.style.display = 'none';
}

toggle.addEventListener('click', function () { isOpen ? closeChat() : openChat(); });
closeBtn.addEventListener('click', closeChat);
document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && isOpen) closeChat(); });

/* ════════════════════════════════════════
   Clear conversation
════════════════════════════════════════ */
clearBtn.addEventListener('click', function () {
    if (isSending && abortCtrl) abortCtrl.abort();
    messages.innerHTML = '';
    suggsEl.style.display = 'block';
    isSending = false;
    setSendMode('idle');
    setStatus('online');
    showWelcome();
});

/* ════════════════════════════════════════
   Welcome card
════════════════════════════════════════ */
function showWelcome() {
    var div = document.createElement('div');
    div.className = 'jdgm-welcome';
    div.innerHTML =
        '<span class="jdgm-welcome-emoji">✝️</span>' +
        '<div class="jdgm-welcome-title">Hi! I\'m the JDGM Assistant</div>' +
        '<div class="jdgm-welcome-body">I can help with questions about our mission, how to donate, volunteer opportunities, and the communities we serve. How can I help you today?</div>';
    messages.appendChild(div);
}

/* ════════════════════════════════════════
   Suggestion chips
════════════════════════════════════════ */
document.querySelectorAll('.jdgm-suggestion').forEach(function (btn) {
    btn.addEventListener('click', function () {
        suggsEl.style.display = 'none';
        sendMessage(btn.dataset.q);
    });
});

/* ════════════════════════════════════════
   Input events
════════════════════════════════════════ */
input.addEventListener('input', function () {
    var len = this.value.trim().length;
    var total = this.value.length;
    sendBtn.disabled = len === 0 || isSending;
    // Auto-resize
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    // Char counter (only show near limit)
    charCount.textContent = total > 800 ? total + '/1000' : '';
});

input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        if (!sendBtn.disabled) doSendOrStop();
    }
});

sendBtn.addEventListener('click', doSendOrStop);

function doSendOrStop() {
    if (isSending) {
        // Stop button: abort the stream
        if (abortCtrl) abortCtrl.abort();
        return;
    }
    var text = input.value.trim();
    if (text) sendMessage(text);
}

/* ════════════════════════════════════════
   Core send — streaming via fetch + ReadableStream
════════════════════════════════════════ */
function sendMessage(text) {
    input.value = '';
    input.style.height = 'auto';
    charCount.textContent = '';
    suggsEl.style.display = 'none';
    isSending = true;
    setSendMode('streaming');
    setStatus('thinking');

    appendUserMessage(text);
    var typingRow = appendTypingRow();

    abortCtrl = new AbortController();

    fetch(STREAM_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'text/event-stream',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ message: text }),
        signal: abortCtrl.signal,
    })
    .then(function (response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.body.getReader();
    })
    .then(function (reader) {
        // Remove typing indicator and create bot bubble
        removeEl(typingRow);
        var botRow    = createBotRow();
        var bubbleEl  = botRow.querySelector('.jdgm-msg-bot');
        var cursorEl  = createCursor();
        bubbleEl.appendChild(cursorEl);
        messages.appendChild(botRow);
        scrollBottom();

        var buffer     = '';
        var rawText    = '';
        var decoder    = new TextDecoder();

        function read() {
            reader.read().then(function (result) {
                if (result.done) {
                    finishStream(bubbleEl, cursorEl, rawText, botRow);
                    return;
                }

                buffer += decoder.decode(result.value, { stream: true });

                // Parse SSE lines
                var lines = buffer.split('\n');
                buffer = lines.pop(); // keep incomplete line in buffer

                lines.forEach(function (line) {
                    line = line.replace(/\r$/, '');
                    if (!line.startsWith('data: ')) return;

                    var payload = line.slice(6);
                    if (payload === '[DONE]') return;

                    try {
                        var parsed = JSON.parse(payload);

                        if (parsed.error) {
                            cursorEl.remove();
                            bubbleEl.innerHTML = renderMarkdown(parsed.message || 'An error occurred.');
                            finishStream(bubbleEl, null, rawText, botRow);
                            return;
                        }

                        if (parsed.delta) {
                            rawText += parsed.delta;
                            // Re-render with cursor at end
                            cursorEl.remove();
                            bubbleEl.innerHTML = renderMarkdown(rawText);
                            bubbleEl.appendChild(createCursor());
                            cursorEl = bubbleEl.querySelector('.jdgm-cursor');
                            scrollBottom();
                        }
                    } catch (_) { /* ignore parse errors */ }
                });

                read();
            }).catch(function (err) {
                if (err.name !== 'AbortError') {
                    removeEl(cursorEl);
                    if (rawText.length === 0) {
                        bubbleEl.innerHTML = renderMarkdown("I'm having trouble connecting right now. Please try again or contact us at info@johnnydavisglobalmissions.org.");
                    }
                    finishStream(bubbleEl, null, rawText, botRow);
                } else {
                    // Aborted by user — just remove cursor and settle
                    finishStream(bubbleEl, cursorEl, rawText + ' *(stopped)*', botRow);
                }
            });
        }

        read();
    })
    .catch(function (err) {
        removeEl(typingRow);
        if (err.name !== 'AbortError') {
            appendBotError("I'm having trouble connecting. Please try again or reach us at info@johnnydavisglobalmissions.org.");
        }
        resetSendState();
    });
}

function finishStream(bubbleEl, cursorEl, rawText, botRow) {
    if (cursorEl) removeEl(cursorEl);
    bubbleEl.innerHTML = renderMarkdown(rawText || '…');
    // Add timestamp to the row
    var timeEl = botRow.querySelector('.jdgm-msg-time');
    if (timeEl) timeEl.textContent = now();
    scrollBottom();
    resetSendState();
}

function resetSendState() {
    isSending = false;
    abortCtrl = null;
    setSendMode('idle');
    setStatus('online');
    sendBtn.disabled = input.value.trim().length === 0;
}

/* ════════════════════════════════════════
   DOM builders
════════════════════════════════════════ */
function appendUserMessage(text) {
    var row = document.createElement('div');
    row.className = 'jdgm-msg-row jdgm-msg-row-user';
    row.innerHTML =
        '<div class="jdgm-msg-time">' + now() + '</div>' +
        '<div class="jdgm-msg jdgm-msg-user">' + escHtml(text) + '</div>';
    messages.appendChild(row);
    scrollBottom();
}

function appendTypingRow() {
    var row = document.createElement('div');
    row.className = 'jdgm-msg-row jdgm-msg-row-bot';
    row.innerHTML =
        '<div class="jdgm-msg-mini-avatar">J</div>' +
        '<div class="jdgm-typing"><span></span><span></span><span></span></div>';
    messages.appendChild(row);
    scrollBottom();
    return row;
}

function createBotRow() {
    var row = document.createElement('div');
    row.className = 'jdgm-msg-row jdgm-msg-row-bot';
    row.innerHTML =
        '<div class="jdgm-msg-mini-avatar">J</div>' +
        '<div>' +
            '<div class="jdgm-msg jdgm-msg-bot"></div>' +
            '<div class="jdgm-msg-time"></div>' +
        '</div>';
    return row;
}

function createCursor() {
    var span = document.createElement('span');
    span.className = 'jdgm-cursor';
    return span;
}

function appendBotError(text) {
    var row = document.createElement('div');
    row.className = 'jdgm-msg-row jdgm-msg-row-bot';
    row.innerHTML =
        '<div class="jdgm-msg-mini-avatar">J</div>' +
        '<div class="jdgm-msg jdgm-msg-bot" style="border-color:#fecaca;background:#fff5f5;color:#b91c1c;">' +
            renderMarkdown(text) +
        '</div>';
    messages.appendChild(row);
    scrollBottom();
}

/* ════════════════════════════════════════
   Helpers
════════════════════════════════════════ */
function renderMarkdown(text) {
    return escHtml(text)
        // **bold**
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        // *italic*
        .replace(/\*(.+?)\*/g, '<em>$1</em>')
        // bullet lists: lines starting with - or *
        .replace(/(?:^|\n)[*\-] (.+)/g, function(_, item) {
            return '<li>' + item + '</li>';
        })
        .replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>')
        // line breaks
        .replace(/\n/g, '<br>');
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function now() {
    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function removeEl(el) {
    if (el && el.parentNode) el.parentNode.removeChild(el);
}

function scrollBottom() {
    messages.scrollTop = messages.scrollHeight;
}

function setSendMode(mode) {
    if (mode === 'streaming') {
        sendIcon.style.display = 'none';
        stopIcon.style.display = 'block';
        sendBtn.disabled = false;
        sendBtn.title = 'Stop generating';
        sendBtn.style.background = 'linear-gradient(135deg,#dc2626,#ef4444)';
    } else {
        sendIcon.style.display = 'block';
        stopIcon.style.display = 'none';
        sendBtn.title = 'Send message';
        sendBtn.style.background = 'linear-gradient(135deg,#0f766e,#14b8a6)';
    }
}

function setStatus(state) {
    if (state === 'thinking') {
        statusDot.style.background    = '#f59e0b';
        statusDot.style.boxShadow     = '0 0 6px rgba(245,158,11,0.6)';
        statusTxt.textContent         = 'Thinking…';
    } else {
        statusDot.style.background    = '#34d399';
        statusDot.style.boxShadow     = '0 0 6px rgba(52,211,153,0.6)';
        statusTxt.textContent         = 'Online';
    }
}

/* ─── Notification badge after 5 s ─── */
setTimeout(function () {
    if (!isOpen && !hasOpened) {
        badge.style.display = 'flex';
        badge.textContent   = '1';
    }
}, 5000);

})();
</script>
