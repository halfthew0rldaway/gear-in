<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // If new conversation requested, create one
        if ($request->has('new') && $request->input('new') == '1') {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'subject' => 'Pertanyaan',
                'status' => 'open',
            ]);
            return redirect()->route('chat.show', $conversation);
        }
        
        $conversations = $user->conversations()
            ->with(['latestMessage', 'admin', 'messages'])
            ->latest('last_message_at')
            ->get();

        return view('storefront.chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $user = auth()->user();
        
        // Ensure user owns this conversation
        abort_unless($conversation->user_id === $user->id, 403);

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $conversation->load(['messages.user', 'admin']);

        return view('storefront.chat.show', compact('conversation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        // Create or get existing open conversation
        $conversation = $user->conversations()
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'subject' => $data['subject'] ?? 'Pertanyaan',
                'status' => 'open',
            ]);
        }

        // Create message
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        // Update conversation last message time
        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()->route('chat.show', $conversation)
            ->with('status', 'Pesan berhasil dikirim.');
    }

    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();
        
        abort_unless($conversation->user_id === $user->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function sendMessageAjax(Request $request)
    {
        $user = auth()->user();
        
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'exists:conversations,id'],
        ]);

        // Get or create conversation
        $conversation = null;
        if ($data['conversation_id']) {
            $conversation = Conversation::where('user_id', $user->id)
                ->findOrFail($data['conversation_id']);
        } else {
            // Create new conversation
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'subject' => 'Pertanyaan',
                'status' => 'open',
            ]);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        $message->load('user');

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'body' => $message->body,
                'created_at' => $message->created_at->format('H:i'),
                'user' => [
                    'name' => $message->user->name,
                ],
            ],
            'conversation_id' => $conversation->id,
        ]);
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $user = auth()->user();
        
        abort_unless($conversation->user_id === $user->id, 403);

        $conversation->load(['messages.user', 'admin']);

        // Mark admin messages as read
        $conversation->messages()
            ->whereHas('user', function ($query) {
                $query->where('role', \App\Models\User::ROLE_ADMIN);
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'subject' => $conversation->subject,
                'admin' => $conversation->admin ? [
                    'name' => $conversation->admin->name,
                ] : null,
            ],
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'created_at' => $message->created_at->format('H:i'),
                    'created_at_full' => $message->created_at->format('d M Y, H:i'),
                    'is_admin' => $message->user->isAdmin(),
                    'user' => [
                        'name' => $message->user->name,
                    ],
                ];
            }),
        ]);
    }
}

