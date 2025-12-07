<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $conversations = Conversation::with(['user', 'latestMessage', 'admin'])
            ->latest('last_message_at')
            ->paginate(20);

        $unreadCount = Conversation::whereHas('messages', function ($query) {
            $query->where('is_read', false)
                ->whereHas('user', function ($q) {
                    $q->where('role', \App\Models\User::ROLE_CUSTOMER);
                });
        })->count();

        return view('admin.chat.index', compact('conversations', 'unreadCount'));
    }

    public function show(Conversation $conversation): View
    {
        $admin = auth()->user();

        // Assign admin to conversation if not assigned
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => $admin->id]);
        }

        // Mark customer messages as read
        $conversation->messages()
            ->whereHas('user', function ($query) {
                $query->where('role', \App\Models\User::ROLE_CUSTOMER);
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $conversation->load(['messages.user', 'user', 'admin']);

        return view('admin.chat.show', compact('conversation'));
    }

    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $admin = auth()->user();

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        try {
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $admin->id,
                'body' => $data['body'],
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);

            // If AJAX request, return JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim.',
                ]);
            }

            return redirect()->route('admin.chat.show', $conversation)
                ->with('status', 'Pesan berhasil dikirim.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesan. Silakan coba lagi.',
                ], 500);
            }
            
            return back()
                ->withInput()
                ->withErrors(['body' => 'Gagal mengirim pesan. Silakan coba lagi.']);
        }
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $admin = auth()->user();

        // Assign admin to conversation if not assigned
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => $admin->id]);
        }

        // Mark customer messages as read
        $conversation->messages()
            ->whereHas('user', function ($query) {
                $query->where('role', \App\Models\User::ROLE_CUSTOMER);
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $conversation->load(['messages.user', 'user', 'admin']);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'subject' => $conversation->subject,
                'user' => [
                    'name' => $conversation->user->name,
                    'email' => $conversation->user->email,
                ],
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

    public function updateStatus(Request $request, Conversation $conversation): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,closed,pending'],
        ]);

        try {
            $conversation->update(['status' => $data['status']]);

            return back()->with('status', 'Status percakapan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['status' => 'Gagal memperbarui status. Silakan coba lagi.']);
        }
    }
}

