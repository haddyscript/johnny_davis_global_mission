<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteLockController extends Controller
{
    private string $flagFile;

    public function __construct()
    {
        $this->flagFile = storage_path('app/site_locked');
    }

    public function showLock()
    {
        return view('site-lock-form', [
            'action' => route('site-lock.lock'),
            'title'  => 'Lock Website',
            'button' => 'Lock Website',
            'locked' => file_exists($this->flagFile),
        ]);
    }

    public function lock(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if ($request->input('password') !== env('SITE_LOCK_PASSWORD')) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        if (file_exists($this->flagFile)) {
            return back()->with('info', 'Website is already locked.');
        }

        file_put_contents($this->flagFile, now()->toIso8601String());

        return back()->with('success', 'Website has been locked. Visitors will see a maintenance page.');
    }

    public function showUnlock()
    {
        return view('site-lock-form', [
            'action' => route('site-lock.unlock'),
            'title'  => 'Unlock Website',
            'button' => 'Unlock Website',
            'locked' => file_exists($this->flagFile),
        ]);
    }

    public function unlock(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if ($request->input('password') !== env('SITE_LOCK_PASSWORD')) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        if (! file_exists($this->flagFile)) {
            return back()->with('info', 'Website is already unlocked.');
        }

        unlink($this->flagFile);

        return back()->with('success', 'Website has been unlocked. Visitors can access the site again.');
    }

    public function locked()
    {
        return response(view('site-locked'), 503);
    }
}
