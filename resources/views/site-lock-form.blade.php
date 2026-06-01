<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title }} — Johnny Davis Global Missions</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: #0f0f0f;
      color: #e5e5e5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .card {
      background: #1a1a1a;
      border: 1px solid #2e2e2e;
      border-radius: 12px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 420px;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: .75rem;
      margin-bottom: 2rem;
    }
    .logo-icon {
      width: 40px; height: 40px;
      background: #fff;
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: .85rem; color: #000; letter-spacing: -.5px;
    }
    .logo-text { font-size: .9rem; color: #999; line-height: 1.3; }
    h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: .5rem; }
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      font-size: .78rem;
      font-weight: 500;
      padding: .25rem .7rem;
      border-radius: 999px;
      margin-bottom: 1.75rem;
    }
    .status-badge.locked   { background: #3b1a1a; color: #f87171; }
    .status-badge.unlocked { background: #1a3b1a; color: #4ade80; }
    .status-badge::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    label { display: block; font-size: .82rem; color: #aaa; margin-bottom: .5rem; }
    input[type="password"] {
      width: 100%;
      background: #111;
      border: 1px solid #333;
      border-radius: 8px;
      padding: .7rem .9rem;
      color: #fff;
      font-size: .95rem;
      outline: none;
      transition: border-color .2s;
    }
    input[type="password"]:focus { border-color: #555; }
    .error-msg { color: #f87171; font-size: .8rem; margin-top: .4rem; }
    button {
      width: 100%;
      margin-top: 1.25rem;
      padding: .8rem;
      border: none;
      border-radius: 8px;
      font-size: .95rem;
      font-weight: 600;
      cursor: pointer;
      background: #fff;
      color: #000;
      transition: opacity .2s;
    }
    button:hover { opacity: .85; }
    .alert {
      border-radius: 8px;
      padding: .75rem 1rem;
      font-size: .85rem;
      margin-bottom: 1.25rem;
    }
    .alert-success { background: #1a3b1a; color: #4ade80; border: 1px solid #2d5a2d; }
    .alert-info    { background: #1a2a3b; color: #60a5fa; border: 1px solid #2d4a6a; }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">
      <div class="logo-icon">JDGM</div>
      <div class="logo-text">Johnny Davis<br>Global Missions</div>
    </div>

    <h1>{{ $title }}</h1>

    <span class="status-badge {{ $locked ? 'locked' : 'unlocked' }}">
      Site is currently {{ $locked ? 'LOCKED' : 'UNLOCKED' }}
    </span>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
      <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <form method="POST" action="{{ $action }}">
      @csrf
      <label for="password">Admin Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter password"
        autocomplete="current-password"
        autofocus
      />
      @error('password')
        <p class="error-msg">{{ $message }}</p>
      @enderror
      <button type="submit">{{ $button }}</button>
    </form>
  </div>
</body>
</html>
