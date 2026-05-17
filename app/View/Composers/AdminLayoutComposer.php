<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Mahasiswa;
use App\Models\ChatSession;

class AdminLayoutComposer
{
    public function compose(View $view)
    {
        // Count pending mahasiswa registrations
        $pendingMahasiswaCount = Mahasiswa::where('status', 'pending')->count();

        // Count new/active chat sessions (not closed)
        $newChatSessionsCount = ChatSession::where('status', '!=', 'closed')
            ->where('is_connected_to_admin', true)
            ->count();

        // Log data to check if it's being passed correctly
        \Log::info('Pending Mahasiswa Count:', ['count' => $pendingMahasiswaCount]);
        \Log::info('New Chat Sessions Count:', ['count' => $newChatSessionsCount]);

        $view->with([
            'pendingMahasiswaCount' => $pendingMahasiswaCount,
            'newChatSessionsCount' => $newChatSessionsCount,
        ]);
    }
}