<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Recent notifications for the dropdown (AJAX).
     */
    public function recent(): \Illuminate\Http\JsonResponse
    {
        $notifications = AdminNotification::latest()->limit(15)->get()->map(function ($n) {
            return [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'message'    => $n->message,
                'icon'       => $n->icon,
                'icon_bg'    => $n->icon_bg,
                'icon_color' => $n->icon_color,
                'is_read'    => $n->is_read,
                'action_url' => $n->data['action_url'] ?? null,
                'time_ago'   => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->format('M j, Y g:i A'),
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => AdminNotification::where('is_read', false)->count(),
        ]);
    }

    /**
     * Unread badge count only (lightweight AJAX poll).
     */
    public function unreadCount(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'unread_count' => AdminNotification::where('is_read', false)->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(AdminNotification $notification): \Illuminate\Http\JsonResponse
    {
        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(): \Illuminate\Http\JsonResponse
    {
        AdminNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Full notifications index page.
     */
    public function index(Request $request)
    {
        $query = AdminNotification::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('unread')) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(30)->withQueryString();
        $unreadCount   = AdminNotification::where('is_read', false)->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }
}
