<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function show(Conversation $conversation)
    {
        // Verify user is a participant
        if (!$conversation->participants->contains(auth()->id())) {
            abort(403, 'You are not a participant in this conversation.');
        }

        return view('chat.index', [
            'selectedConversationId' => $conversation->id,
        ]);
    }
}
