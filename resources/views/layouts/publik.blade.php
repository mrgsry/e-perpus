<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPUSAKA') — Sistem Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --brand-primary: #2563eb;
            --brand-dark:    #1d4ed8;
            --brand-light:   #eff6ff;
            --bg-page:       #f8fafc;
            --bg-white:      #ffffff;
            --text-primary:  #0f172a;
            --text-muted:    #64748b;   
            --text-light:    #94a3b8;
            --border:        #e2e8f0;
            --nav-h:         64px;
            --font:          'Plus Jakarta Sans', sans-serif;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     16px;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font);
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }
        .sipusaka-nav {
            position: sticky; top: 0; z-index: 100; height: var(--nav-h);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .nav-inner {
            max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; height: 100%;
            display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
        }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; }
        .nav-brand-icon {
            width: 36px; height: 36px; background: var(--brand-primary); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-brand-icon svg { width: 20px; height: 20px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .nav-brand-text { font-size: 1.125rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; }
        .nav-brand-text span { color: var(--brand-primary); }
        .nav-links { display: flex; align-items: center; gap: 0.25rem; list-style: none; }
        .nav-links a {
            display: flex; align-items: center; gap: 6px; padding: 0.5rem 0.875rem;
            font-size: 0.875rem; font-weight: 500; color: var(--text-muted);
            text-decoration: none; border-radius: var(--radius-sm); transition: all 0.15s;
        }
        .nav-links a:hover { color: var(--brand-primary); background: var(--brand-light); }
        .nav-links a.active { color: var(--brand-primary); background: var(--brand-light); font-weight: 600; }
        .nav-toggle { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 6px; border: none; background: none; border-radius: var(--radius-sm); }
        .nav-toggle span { display: block; width: 22px; height: 2px; background: var(--text-primary); border-radius: 2px; transition: all 0.25s; }
        main { flex: 1; }
        .sipusaka-footer { background: var(--bg-white); border-top: 1px solid var(--border); padding: 1.5rem; }
        .footer-inner { max-width: 1100px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
        .footer-brand { display: flex; align-items: center; gap: 8px; }
        .footer-brand-dot { width: 8px; height: 8px; background: var(--brand-primary); border-radius: 50%; }
        .footer-brand-name { font-size: 0.875rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.01em; }
        .footer-copy { font-size: 0.8rem; color: var(--text-light); }
        @stack('styles')
        @media (max-width: 640px) {
            .nav-links { display: none; position: absolute; top: var(--nav-h); left: 0; right: 0; background: var(--bg-white); border-bottom: 1px solid var(--border); flex-direction: column; padding: 0.75rem 1rem; gap: 0.25rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
            .nav-links.open { display: flex; }
            .nav-toggle { display: flex; }
            .footer-inner { justify-content: center; text-align: center; }
        }

        /* CHAT WIDGET STYLES */
        .chat-widget-container { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
        .chat-toggle {
            width: 60px; height: 60px; border-radius: 50%; background: var(--brand-primary);
            border: none; color: white; cursor: pointer; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4); transition: all 0.3s ease;
        }
        .chat-toggle:hover { transform: scale(1.05); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5); }
        .chat-toggle svg { width: 28px; height: 28px; }
        .chat-badge {
            position: absolute; top: 0; right: 0; width: 20px; height: 20px; background: #ef4444;
            border-radius: 50%; font-size: 11px; display: flex; align-items: center;
            justify-content: center; color: white; font-weight: 600;
        }
        .chat-box {
            position: absolute; bottom: 75px; right: 0; width: 350px; height: 450px;
            background: white; border-radius: var(--radius-lg); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: none; flex-direction: column; overflow: hidden; border: 1px solid var(--border);
        }
        .chat-box.open { display: flex; }
        .chat-header {
            padding: 1rem; background: linear-gradient(135deg, var(--brand-primary), var(--brand-dark));
            color: white; display: flex; align-items: center; justify-content: space-between;
        }
        .chat-title { font-weight: 600; font-size: 0.95rem; }
        .chat-close {
            background: none; border: none; color: white; cursor: pointer; width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.2s;
        }
        .chat-close:hover { background: rgba(255, 255, 255, 0.2); }
        .chat-close svg { width: 18px; height: 18px; }
        .chat-messages { flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; background: #f8fafc; }
        .chat-message { max-width: 80%; display: flex; flex-direction: column; gap: 0.25rem; }
        .chat-message.user { align-self: flex-end; }
        .chat-message.bot, .chat-message.admin { align-self: flex-start; }
        .chat-message-text { padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.875rem; line-height: 1.5; word-wrap: break-word; }
        .chat-message.user .chat-message-text { background: var(--brand-primary); color: white; border-bottom-right-radius: 4px; }
        .chat-message.bot .chat-message-text, .chat-message.admin .chat-message-text { background: white; color: var(--text-primary); border: 1px solid var(--border); border-bottom-left-radius: 4px; }
        .chat-message-time { font-size: 0.7rem; color: var(--text-light); margin-top: 0.25rem; }
        .chat-message.user .chat-message-time { text-align: right; }
        .chat-status { padding: 0.5rem 1rem; background: #ecfdf5; border-top: 1px solid var(--border); display: none; }
        .status-badge { font-size: 0.75rem; color: #059669; font-weight: 500; }
        .chat-form { padding: 1rem; border-top: 1px solid var(--border); display: flex; gap: 0.5rem; background: white; }
        .chat-form input[type="text"] {
            flex: 1; padding: 0.625rem 1rem; border: 1px solid var(--border); border-radius: var(--radius-md);
            font-size: 0.875rem; font-family: var(--font); outline: none; transition: border-color 0.2s;
        }
        .chat-form input[type="text"]:focus { border-color: var(--brand-primary); }
        .chat-form button {
            width: 40px; height: 40px; border: none; background: var(--brand-primary); color: white;
            border-radius: var(--radius-md); cursor: pointer; display: flex; align-items: center;
            justify-content: center; transition: background 0.2s;
        }
        .chat-form button:hover { background: var(--brand-dark); }
        .chat-form button svg { width: 18px; height: 18px; }
        .chat-typing { display: flex; gap: 0.25rem; padding: 0.75rem 1rem; }
        .chat-typing span { width: 8px; height: 8px; background: var(--text-light); border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; }
        .chat-typing span:nth-child(1) { animation-delay: -0.32s; }
        .chat-typing span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
        @media (max-width: 480px) { .chat-box { width: calc(100vw - 40px); height: 60vh; } }
    </style>
    @stack('head_styles')
</head>
<body>

    <nav class="sipusaka-nav">
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                </div>
                <span class="nav-brand-text">SIPU<span>SAKA</span></span>
            </a>
            <ul class="nav-links" id="navMenu">
                <li>
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                        Katalog Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('publik.register.form') }}" class="{{ request()->routeIs('publik.register.form') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        Register
                    </a>
                </li>
                <li>
                    <a href="{{ route('publik.cek-status') }}" class="{{ request()->routeIs('publik.cek-status*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Cek Status Peminjaman
                    </a>
                </li>
            </ul>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main>@yield('content')</main>

    <footer class="sipusaka-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="footer-brand-dot"></div>
                <span class="footer-brand-name">SIPUSAKA</span>
            </div>
            <span class="footer-copy">&copy; {{ date('Y') }} Sistem Informasi Perpustakaan. Semua hak dilindungi.</span>
        </div>
    </footer>

    {{-- CHAT WIDGET --}}
    <div class="chat-widget-container">
        <button class="chat-toggle" id="chatToggle" aria-label="Buka chat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span class="chat-badge" id="chatBadge" style="display: none;"></span>
        </button>
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <span class="chat-title">&#x1F4AC; SIPUSAKA Assistant</span>
                <button class="chat-close" id="chatClose" aria-label="Tutup chat">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be added here -->
            </div>
            <div class="chat-status" id="chatStatus">
                <span class="status-badge">&#x1F7E2; Terhubung dengan Admin</span>
            </div>
            <form class="chat-form" id="chatForm">
                @csrf
                <input type="hidden" id="chatSessionId" name="session_id" value="">
                <input type="hidden" id="lastMessageId" value="0">
                <input type="text" id="chatInput" name="message" placeholder="Ketik pesan Anda..." autocomplete="off" required>
                <button type="submit" aria-label="Kirim">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
    (function() {
        'use strict';

        // Elements
        var chatToggle = document.getElementById('chatToggle');
        var chatClose = document.getElementById('chatClose');
        var chatBox = document.getElementById('chatBox');
        var chatForm = document.getElementById('chatForm');
        var chatInput = document.getElementById('chatInput');
        var chatMessages = document.getElementById('chatMessages');
        var chatSessionId = document.getElementById('chatSessionId');
        var lastMessageId = document.getElementById('lastMessageId');
        var chatStatus = document.getElementById('chatStatus');
        var chatBadge = document.getElementById('chatBadge');

        // State
        var isChatOpen = false;
        var isConnectedToAdmin = false;
        var pollInterval = null;
        var isVerified = false;
        var awaitingNimInput = false;

        // CSRF Token
        var csrfToken = document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '';

        // Toggle chat open/close
        function toggleChat() {
            openChat();
        }

        function openChat() {
            isChatOpen = true;
            chatBox.classList.add('open');
            
            // Show welcome message if first time opening
            if (chatMessages.children.length === 0) {
                addMessage('bot', 'Halo! Selamat datang di SIPUSAKA Assistant. 👋');
                setTimeout(function() {
                    addMessage('bot', 'Untuk memulai, silakan masukkan NIM Anda.');
                    awaitingNimInput = true;
                }, 500);
            }
            
            chatInput.focus();
        }

        function closeChat() {
            isChatOpen = false;
            chatBox.classList.remove('open');
            stopPolling();
        }

        // Verify NIM
        function verifyNim(nim) {
            showTyping();
            
            fetch('{{ route("chat.verify-nim") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nim: nim })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                hideTyping();
                
                if (data.success) {
                    isVerified = true;
                    awaitingNimInput = false;
                    addMessage('bot', 'Terima kasih! Anda terdaftar sebagai ' + data.mahasiswa.nama + ' ✓');
                    setTimeout(function() {
                        addMessage('bot', 'Ada yang bisa saya bantu hari ini?');
                        startPolling();
                    }, 800);
                } else {
                    addMessage('bot', '❌ ' + (data.message || 'NIM tidak terdaftar. Silakan coba lagi atau hubungi admin.'));
                    awaitingNimInput = true;
                }
            })
            .catch(function(err) {
                hideTyping();
                addMessage('bot', '❌ Terjadi kesalahan. Silakan coba lagi.');
                awaitingNimInput = true;
            });
        }

        // Add message to chat
        function addMessage(sender, text) {
            var messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message ' + sender;
            var time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            var escapedText = escapeHtml(text);
            messageDiv.innerHTML = '<div class="chat-message-text">' + escapedText + '</div><div class="chat-message-time">' + time + '</div>';
            chatMessages.appendChild(messageDiv);
            scrollToBottom();
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show/hide typing indicator
        function showTyping() {
            var typingDiv = document.createElement('div');
            typingDiv.className = 'chat-message bot chat-typing';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = '<span></span><span></span><span></span>';
            chatMessages.appendChild(typingDiv);
            scrollToBottom();
        }

        function hideTyping() {
            var typing = document.getElementById('typingIndicator');
            if (typing) typing.remove();
        }

        // Send message
        function sendMessage(message) {
            addMessage('user', message);
            chatInput.value = '';
            showTyping();

            fetch('{{ route("chat.send") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    session_id: chatSessionId.value || null,
                    message: message
                })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                hideTyping();

                if (data.session_id) {
                    chatSessionId.value = data.session_id;
                }

                if (data.is_connected_to_admin && !isConnectedToAdmin) {
                    isConnectedToAdmin = true;
                    chatStatus.style.display = 'block';
                    addMessage('bot', '\u{1F7E2} Anda sekarang terhubung dengan Admin.');
                }

                if (data.message) {
                    addMessage('bot', data.message);
                }
            })
            .catch(function(err) {
                hideTyping();
                console.error('Error:', err);
            });
        }

        // Polling for new messages
        function pollMessages() {
            if (!chatSessionId.value) return;

            fetch('{{ route("chat.messages") }}?session_id=' + encodeURIComponent(chatSessionId.value) + '&last_message_id=' + encodeURIComponent(lastMessageId.value), {
                credentials: 'same-origin'
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(function(msg) {
                        if (msg.sender_type === 'admin') {
                            addMessage('admin', msg.message);
                        } else if (msg.sender_type === 'bot') {
                            addMessage('bot', msg.message);
                        }
                        if (msg.id > lastMessageId.value) {
                            lastMessageId.value = msg.id;
                        }
                    });
                }

                if (data.is_connected_to_admin && !isConnectedToAdmin) {
                    isConnectedToAdmin = true;
                    chatStatus.style.display = 'block';
                    addMessage('bot', '\u{1F7E2} Anda sekarang terhubung dengan Admin.');
                }
            })
            .catch(function(err) {
                console.error("Error polling:", err);
            });
        }

        function startPolling() {
            pollMessages();
            pollInterval = setInterval(pollMessages, 3000);
        }

        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        // Event Listeners
        chatToggle.addEventListener('click', toggleChat);
        chatClose.addEventListener('click', closeChat);

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var message = chatInput.value.trim();
            if (!message) return;
            
            // If awaiting NIM input, verify NIM instead of sending message
            if (awaitingNimInput && !isVerified) {
                addMessage('user', message);
                chatInput.value = '';
                verifyNim(message);
            } else if (isVerified) {
                sendMessage(message);
            } else {
                addMessage('user', message);
                chatInput.value = '';
                addMessage('bot', 'Silakan masukkan NIM Anda terlebih dahulu.');
            }
        });

        // Nav toggle
        var navToggle = document.getElementById('navToggle');
        var navMenu = document.getElementById('navMenu');
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', function() {
                navMenu.classList.toggle('open');
            });
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>
