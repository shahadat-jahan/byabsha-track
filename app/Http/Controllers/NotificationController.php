<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display paginated notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Return recent notifications for dropdown or AJAX consumers.
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = max(1, min((int) $request->query('limit', 10), 20));

        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function (Notification $notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'is_unread' => $notification->isUnread(),
                    'url' => $notification->data['url'] ?? '#',
                    'created_at' => $notification->created_at,
                    'created_at_human' => $notification->created_at?->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $request->user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('notifications.marked_as_read'),
                'unread_count' => $request->user()->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', __('notifications.marked_as_read'));
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('notifications.all_marked_as_read'),
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', __('notifications.all_marked_as_read'));
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('notifications.deleted'),
                'unread_count' => $request->user()->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', __('notifications.deleted'));
    }

    /**
     * Delete all read notifications for the authenticated user.
     */
    public function deleteAllRead(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()
            ->notifications()
            ->read()
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('notifications.all_read_deleted'),
                'unread_count' => $request->user()->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', __('notifications.all_read_deleted'));
    }
}
