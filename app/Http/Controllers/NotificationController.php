<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\MemberNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = MemberNotification::with('sender')
            ->where('user_id', $user->id)
            ->latest();

        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        // Filter by read/unread
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(20);

        $unreadCount = MemberNotification::where('user_id', $user->id)->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Show form to create a new notification (admin only)
     */
    public function create()
    {
        // Only admin can access this
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        // Get all members for individual selection
        $members = User::role('member')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'membership_number', 'email']);

        return view('notifications.create', compact('members'));
    }

    /**
     * Send notification to users (admin only)
     */
    public function send(Request $request)
    {
        // Only admin can send notifications
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:admin_message,loan_update,savings_update,meeting,system',
            'recipient_type' => 'required|in:all,role,specific',
            'role' => 'required_if:recipient_type,role|nullable|in:member,treasurer',
            'recipients' => 'required_if:recipient_type,specific|nullable|array',
            'recipients.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $senderId = auth()->id();
            $type = $request->type;
            $title = $request->title;
            $message = $request->message;
            $data = $request->has('data') ? json_decode($request->data, true) : null;

            $recipientIds = [];

            // Determine recipients based on selection
            if ($request->recipient_type === 'all') {
                // Send to all users (or just members? Let's send to all)
                $recipientIds = User::where('status', 'active')->pluck('id')->toArray();
            } elseif ($request->recipient_type === 'role') {
                // Send to specific role
                $recipientIds = User::role($request->role)
                    ->where('status', 'active')
                    ->pluck('id')
                    ->toArray();
            } else {
                // Send to specific users
                $recipientIds = $request->recipients;
            }

            // Create notifications for each recipient
            foreach ($recipientIds as $userId) {
                MemberNotification::create([
                    'user_id' => $userId,
                    'sender_id' => $senderId,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'data' => $data,
                    'read_at' => null
                ]);
            }

            DB::commit();

            $count = count($recipientIds);
            return redirect()->route('notifications.create')
                ->with('success', "Notification sent successfully to {$count} recipient(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to send notification: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = MemberNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllRead()
    {
        MemberNotification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get unread count for AJAX
     */
    public function getUnreadCount()
    {
        $count = MemberNotification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for header dropdown (AJAX)
     */
    public function getRecent()
    {
        $notifications = MemberNotification::with('sender')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'is_read' => $notification->isRead(),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => route('notifications.show', $notification->id)
                ];
            });

        $unreadCount = MemberNotification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Show single notification
     */
    public function show($id)
    {
        $notification = MemberNotification::with('sender')
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        // Mark as read when viewed
        $notification->markAsRead();

        return view('notifications.show', compact('notification'));
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = MemberNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        MemberNotification::where('user_id', auth()->id())
            ->read()
            ->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'All read notifications deleted.');
    }

    /**
     * API endpoint to get notifications for a specific user (for admin)
     */
    public function getUserNotifications(Request $request, $userId)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);

        $notifications = MemberNotification::with('sender')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(20);

        return response()->json([
            'user' => $user->name,
            'notifications' => $notifications
        ]);
    }
}
