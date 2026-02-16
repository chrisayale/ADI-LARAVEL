<?php
// app/Http/Controllers/Admin/MessageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberNotification;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create()
    {
        $treasurers = User::role('treasurer')->get();
        $members = User::role('member')->paginate(20); // For selection
        return view('admin.messages.create', compact('treasurers', 'members'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all_members,all_treasurers,specific_users',
            'user_ids' => 'required_if:recipient_type,specific_users|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:admin_message,announcement,reminder'
        ]);

        $recipients = [];

        // Determine recipients based on selection
        if ($request->recipient_type === 'all_members') {
            $recipients = User::role('member')->pluck('id')->toArray();
        } elseif ($request->recipient_type === 'all_treasurers') {
            $recipients = User::role('treasurer')->pluck('id')->toArray();
        } else {
            $recipients = $request->user_ids;
        }

        // Create notifications for all recipients
        foreach ($recipients as $userId) {
            MemberNotification::create([
                'user_id' => $userId,
                'sender_id' => auth()->id(),
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'data' => ['sent_by' => auth()->user()->name]
            ]);
        }

        return redirect()->route('admin.messages.sent')
            ->with('success', 'Message sent to ' . count($recipients) . ' recipients');
    }

    public function sent()
    {
        $messages = MemberNotification::where('sender_id', auth()->id())
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.messages.sent', compact('messages'));
    }
}
