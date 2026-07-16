@extends('layouts.admin')

@section('title', 'Chat Detail')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-comment-dots"></i> Percakapan dengan Mahasiswa
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Chat</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i>
                            <strong>{{ $session->mahasiswa_nama ?? 'Mahasiswa' }}</strong>
                            <span class="text-muted">(NIM: {{ $session->mahasiswa_nim ?? '-' }})</span>
                        </h3>
                        <div class="card-tools">
                            @if($session->is_connected_to_admin)
                            <span class="badge badge-success mr-2">
                                <i class="fas fa-check-circle"></i> Terhubung
                            </span>
                            @else
                            <span class="badge badge-warning mr-2">
                                <i class="fas fa-robot"></i> Bot
                            </span>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger" onclick="closeSession()">
                                <i class="fas fa-times"></i> Tutup Sesi
                            </button>
                        </div>
                    </div>

                    <div class="card-body" style="height: 500px; overflow-y: auto; background: #f8f9fa;"
                        id="chatMessagesContainer">
                        <div id="chatMessages">
                            <!-- Messages will be loaded here -->
                        </div>
                    </div>

                    <div class="card-footer">
                        <form id="chatForm" class="d-flex">
                            @csrf
                            <input type="hidden" id="sessionId" value="{{ $session->session_id }}">
                            <input type="hidden" id="lastMessageId" value="0">
                            <input type="text" id="messageInput" class="form-control" placeholder="Ketik pesan Anda..."
                                autocomplete="off" required>
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#chatMessages {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 16px;
}

.chat-message {
    display: flex;
    margin-bottom: 8px;
}

.chat-message.user {
    justify-content: flex-start;
}

.chat-message.admin {
    justify-content: flex-end;
}

.chat-message.bot {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 12px;
    word-wrap: break-word;
    position: relative;
}

.chat-message.user .message-bubble {
    background: #e3f2fd;
    color: #0d47a1;
    border-bottom-left-radius: 4px;
}

.chat-message.admin .message-bubble {
    background: #2563eb;
    color: white;
    border-bottom-right-radius: 4px;
}

.chat-message.bot .message-bubble {
    background: #f5f5f5;
    color: #333;
    border: 1px solid #e0e0e0;
    border-bottom-left-radius: 4px;
}

.message-sender {
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 4px;
}

.chat-message.user .message-sender {
    color: #1565c0;
}

.chat-message.admin .message-sender {
    color: #e3f2fd;
}

.chat-message.bot .message-sender {
    color: #666;
}

.message-text {
    font-size: 0.95rem;
    line-height: 1.4;
}

.message-time {
    font-size: 0.75rem;
    margin-top: 4px;
    opacity: 0.7;
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 10px;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {

    0%,
    80%,
    100% {
        transform: scale(0);
    }

    40% {
        transform: scale(1);
    }
}
</style>

<script>
(function() {
    'use strict';

    const sessionId = document.getElementById('sessionId').value;
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const chatContainer = document.getElementById('chatMessagesContainer');
    const lastMessageIdInput = document.getElementById('lastMessageId');
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const sendButton = chatForm.querySelector('button[type="submit"]');

    let pollInterval = null;
    let isLoadingMessages = false;
    const renderedMessageIds = new Set();

    // Format time
    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Add message to chat
    function addMessage(msg) {
        if (msg.id && renderedMessageIds.has(msg.id)) {
            return;
        }

        const emptyState = chatMessages.querySelector('.chat-empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = 'chat-message ' + msg.sender_type;

        let senderName = '';
        if (msg.sender_type === 'user') {
            senderName = 'Mahasiswa';
        } else if (msg.sender_type === 'admin') {
            senderName = 'Anda';
        } else {
            senderName = 'Bot';
        }

        messageDiv.innerHTML = `
            <div class="message-bubble">
                <div class="message-sender">${senderName}</div>
                <div class="message-text">${escapeHtml(msg.message)}</div>
                <div class="message-time">${formatTime(msg.created_at)}</div>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        scrollToBottom();

        if (msg.id) {
            renderedMessageIds.add(msg.id);
        }

        // Update last message ID
        if (msg.id > parseInt(lastMessageIdInput.value)) {
            lastMessageIdInput.value = msg.id;
        }
    }

    // Scroll to bottom
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Load messages newer than the current cursor. A request guard prevents polling races.
    function loadMessages() {
        if (isLoadingMessages) {
            return Promise.resolve();
        }

        isLoadingMessages = true;
        const url = '{{ route("admin.chat.messages", ":sessionId") }}'
            .replace(':sessionId', sessionId) + '?last_message_id=' + encodeURIComponent(lastMessageIdInput.value);

        return fetch(url, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal memuat pesan.');
                }

                return response.json();
            })
            .then(data => {
                (data.messages || []).forEach(addMessage);
            })
            .catch(err => {
                console.error('Error loading messages:', err);
            })
            .finally(() => {
                isLoadingMessages = false;
            });
    }

    // Load initial messages
    function loadInitialMessages() {
        chatMessages.innerHTML = '';
        lastMessageIdInput.value = '0';
        renderedMessageIds.clear();

        return loadMessages().then(() => {
            if (!chatMessages.children.length) {
                chatMessages.innerHTML =
                    '<div class="text-center text-muted chat-empty-state">Belum ada pesan</div>';
            }
        });
    }

    // Send message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        messageInput.value = '';
        messageInput.disabled = true;
        sendButton.disabled = true;

        const sendUrl = '{{ route("admin.chat.send") }}';
        fetch(sendUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    message: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengirim pesan.');
                }

                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    addMessage(data.message);
                }
            })
            .catch(err => {
                console.error('Error sending message:', err);
                showAlert('Gagal mengirim pesan. Silakan coba lagi.');
                messageInput.value = message;
            })
            .finally(() => {
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
            });
    });

    // Start polling for new messages
    function startPolling() {
        if (pollInterval) {
            return;
        }

        loadMessages();
        pollInterval = setInterval(loadMessages, 3000);
    }

    // Close session
    window.closeSession = function() {
        showConfirm('Apakah Anda yakin ingin menutup sesi chat ini?')
            .then(function(confirmed) {
                if (!confirmed) {
                    return;
                }
                const closeUrl = '{{ route("admin.chat.close", ":sessionId") }}'.replace(':sessionId',
                    sessionId);
                fetch(closeUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showAlert('Sesi chat telah ditutup.').then(() => {
                                window.location.href = '{{ route("admin.chat.index") }}';
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Error closing session:', err);
                        showAlert('Gagal menutup sesi. Silakan coba lagi.');
                    });
            });
    };

    // Load history first, then begin polling so the cursor cannot race with the initial request.
    loadInitialMessages().finally(startPolling);

    // Focus input
    messageInput.focus();
})();
</script>
@endsection