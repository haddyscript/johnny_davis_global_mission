<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Recent notifications for the dropdown (AJAX).
     */
    public function recent(): JsonResponse
    {
        $notifications = AdminNotification::latest()->limit(15)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'icon_bg' => $n->icon_bg,
                'icon_color' => $n->icon_color,
                'is_read' => $n->is_read,
                'action_url' => $n->data['action_url'] ?? null,
                'time_ago' => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->format('M j, Y g:i A'),
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => AdminNotification::where('is_read', false)->count(),
        ]);
    }

    /**
     * Unread badge count only (lightweight AJAX poll).
     */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'unread_count' => AdminNotification::where('is_read', false)->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(AdminNotification $notification): JsonResponse
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
    public function markAllRead(): JsonResponse
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

        // "All Status" submits unread= (empty), so only a real "1" means unread-only
        if ($request->boolean('unread')) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(30)->withQueryString();
        $unreadCount = AdminNotification::where('is_read', false)->count();
        $totalCount = AdminNotification::count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'totalCount'));
    }
}
