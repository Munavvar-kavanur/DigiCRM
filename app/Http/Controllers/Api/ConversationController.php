<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $conversations = $request->user()
            ->conversations()
            ->with(['latestMessage', 'participants', 'project'])
            ->get()
            ->map(function ($conversation) use ($request) {
                return [
                    'id' => $conversation->id,
                    'title' => $conversation->title,
                    'type' => $conversation->type,
                    'project_id' => $conversation->project_id,
                    'project' => $conversation->project ? [
                        'id' => $conversation->project->id,
                        'title' => $conversation->project->title,
                    ] : null,
                    'participants' => $conversation->participants->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }),
                    'last_message' => $conversation->latestMessage->first() ? [
                        'message' => $conversation->latestMessage->first()->message,
                        'created_at' => $conversation->latestMessage->first()->created_at,
                        'user' => [
                            'name' => $conversation->latestMessage->first()->user->name,
                        ],
                    ] : null,
                    'unread_count' => $conversation->getUnreadCountForUser($request->user()->id),
                    'last_message_at' => $conversation->last_message_at,
                ];
            });

        return response()->json($conversations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:direct,group,project,support',
            'title' => 'required_if:type,group,project|string|max:255',
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $conversation = Conversation::create([
            'title' => $validated['title'] ?? null,
            'type' => $validated['type'],
            'project_id' => $validated['project_id'] ?? null,
            'branch_id' => $request->user()->branch_id,
            'created_by' => $request->user()->id,
            'last_message_at' => now(),
        ]);

        // Add creator
        $conversation->participants()->attach($request->user()->id, ['last_read_at' => now()]);

        // Add other participants
        foreach ($validated['participant_ids'] as $userId) {
            if ($userId != $request->user()->id) {
                $conversation->participants()->attach($userId);
            }
        }

        return response()->json([
            'id' => $conversation->id,
            'message' => 'Conversation created successfully',
        ], 201);
    }

    public function show(Request $request, Conversation $conversation)
    {
        if (!$conversation->participants->contains($request->user()->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()->with('user')->get()->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                ],
                'created_at' => $message->created_at,
                'is_mine' => $message->user_id === auth()->id(),
            ];
        });

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'type' => $conversation->type,
            ],
            'messages' => $messages,
        ]);
    }

    public function markAsRead(Request $request, Conversation $conversation)
    {
        if (!$conversation->participants->contains($request->user()->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->markAsReadFor($request->user()->id);

        return response()->json(['message' => 'Marked as read']);
    }
}
