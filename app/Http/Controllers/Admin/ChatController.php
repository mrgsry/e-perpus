<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['user', 'admin'])
            ->whereIn('status', ['active'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active sessions (last activity within 5 minutes)
        $activeSessions = ChatSession::with(['mahasiswa', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->whereIn('status', ['active'])
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.chat.index', compact('sessions', 'activeSessions'));
    }

    public function show($sessionId)
    {
        $session = ChatSession::with(['user', 'messages.user'])
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.chat.show', compact('session', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id');
        $message = trim($request->input('message'));
        $adminId = auth()->id();

        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();

        if (!$session->is_connected_to_admin) {
            $session->is_connected_to_admin = true;
        }

        if (!$session->admin_id) {
            $session->admin_id = $adminId;
        }

        $session->save();

        $chatMessage = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $adminId,
            'sender_type' => 'admin',
            'message' => $message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $chatMessage,
        ]);
    }

    public function closeSession(Request $request, $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();

        $session->status = 'closed';
        $session->save();

        // Add system message to notify the student
        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
            'sender_type' => 'bot',
            'message' => 'Sesi chat telah ditutup oleh Admin. Terima kasih telah menggunakan layanan SIPUSAKA! Jika butuh bantuan lagi, silakan mulai chat baru.',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Session closed successfully',
        ]);
    }

    public function getNewMessages(Request $request, $sessionId)
    {
        $lastMessageId = $request->input('last_message_id', 0);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }
}