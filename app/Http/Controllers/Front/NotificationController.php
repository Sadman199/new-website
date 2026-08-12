<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\UserNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private UserNotificationService $notifications
    ) {}

    public function index(Request $request): JsonResponse
    {
        $items = $this->notifications
            ->recent($request->user()->id, 15)
            ->map(fn (UserNotification $n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'url' => $n->url,
                'read' => $n->read_at !== null,
                'time' => $n->created_at?->diffForHumans(),
            ]);

        return response()->json([
            'unread' => $this->notifications->unreadCount($request->user()->id),
            'items' => $items,
        ]);
    }

    public function markRead(Request $request, UserNotification $notification): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ((int) $notification->user_id !== (int) $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            abort(403);
        }

        $this->notifications->markRead($notification, $request->user()->id);

        if ($request->expectsJson()) {
            return response()->json([
                'unread' => $this->notifications->unreadCount($request->user()->id),
            ]);
        }

        return redirect()->back();
    }

    public function markAllRead(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->notifications->markAllRead($request->user()->id);

        if ($request->expectsJson()) {
            return response()->json(['unread' => 0]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
