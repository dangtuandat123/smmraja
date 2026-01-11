<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user's notifications (for AJAX polling)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $notifications = Notification::forUser($user->id)
            ->latest()
            ->limit(20)
            ->get();

        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon,
                    'color' => $n->color,
                    'is_read' => $n->is_read,
                    'time_ago' => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->format('d/m/Y H:i'),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count only (lightweight polling)
     */
    public function unreadCount()
    {
        $count = Notification::forUser(auth()->id())->unread()->count();
        
        return response()->json([
            'unread_count' => $count,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
