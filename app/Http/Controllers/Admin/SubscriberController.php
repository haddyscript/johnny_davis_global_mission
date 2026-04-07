<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('email',      'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subscribers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => NewsletterSubscriber::count(),
            'active'   => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'stats'));
    }

    public function toggleActive(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        return response()->json(['is_active' => $subscriber->is_active]);
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()
            ->route('admin.subscribers.index')
            ->with('success', 'Subscriber removed.');
    }
}
