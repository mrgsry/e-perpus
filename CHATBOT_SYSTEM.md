# Chatbot System Documentation

This document outlines the implementation of the chatbot system, designed to provide automated assistance to members and seamlessly connect them with an admin when needed.

## 1. Introduction

The chatbot system integrates a floating chat widget into the public-facing application. It features an intelligent virtual assistant that responds to common queries. If the bot fails to understand a query multiple times, or if the user sends a certain number of messages, the chat session is automatically escalated to a live admin. Admins can manage and close chat sessions, and members are notified when a session is closed, prompting them to start a new one.

## 2. Database Schema

The system relies on two main tables: `chat_sessions` and `chat_messages`.

### `chat_sessions` Table

Stores information about each chat conversation.

| Column Name           | Type       | Description                                     |
| :-------------------- | :--------- | :---------------------------------------------- |
| `id`                  | `bigint`   | Primary key                                     |
| `session_id`          | `string`   | Unique UUID for the chat session                |
| `user_id`             | `bigint`   | Foreign key to `users` table (member)           |
| `admin_id`            | `bigint`   | Foreign key to `users` table (admin, nullable)  |
| `bot_fail_count`      | `integer`  | Number of times the bot failed to respond       |
| `is_connected_to_admin` | `boolean`  | Flag indicating if session is connected to admin |
| `status`              | `string`   | `active` or `closed`                            |
| `created_at`          | `timestamp`|                                                 |
| `updated_at`          | `timestamp`|                                                 |

### `chat_messages` Table

Stores individual messages within a chat session.

| Column Name   | Type       | Description                                     |
| :------------ | :--------- | :---------------------------------------------- |
| `id`          | `bigint`   | Primary key                                     |
| `session_id`  | `string`   | Foreign key to `chat_sessions` table            |
| `user_id`     | `bigint`   | Foreign key to `users` table (sender)           |
| `sender_type` | `string`   | `user`, `bot`, or `admin`                       |
| `message`     | `text`     | Content of the message                          |
| `created_at`  | `timestamp`|                                                 |
| `updated_at`  | `timestamp`|                                                 |

## 3. Backend (Laravel)

### Models

-   **`App\Models\ChatSession.php`**: Eloquent model for the `chat_sessions` table. Defines relationships with `User` (for both member and admin) and `ChatMessage`.
-   **`App\Models\ChatMessage.php`**: Eloquent model for the `chat_messages` table. Defines relationship with `ChatSession`.

### Controllers

#### `App\Http\Controllers\Public\ChatController.php`

Handles member-facing chat logic:

-   **`sendMessage(Request $request)`**:
    -   Receives user messages.
    -   Creates or retrieves a `ChatSession`.
    -   Saves user messages.
    -   **Auto-connect to Admin Logic**:
        -   If the user sends 3 or more messages, the session's `is_connected_to_admin` flag is set to `true`.
        -   If the bot fails to provide an intelligent response 3 times (`bot_fail_count`), the session is also connected to an admin.
    -   Provides intelligent bot responses based on keywords.
    -   If connected to admin, it indicates that the message has been forwarded to the admin.
-   **`getMessages(Request $request)`**:
    -   Retrieves chat messages for a given `session_id`.
    -   Can filter for new messages using `last_message_id`.
    -   **Crucially, it now returns the `session_closed` status**, allowing the frontend to react when an admin closes the session.
-   **`getIntelligentResponse(string $message)`**:
    -   Private helper method containing keyword-based logic to generate bot responses.

#### `App\Http\Controllers\Admin\ChatController.php`

Handles admin-facing chat management:

-   **`index()`**: Displays a list of active chat sessions for admins.
-   **`show($sessionId)`**: Shows the detailed view of a specific chat session for an admin.
-   **`sendMessage(Request $request)`**: Allows an admin to send a message to a member. Automatically sets `is_connected_to_admin` and `admin_id` if not already set.
-   **`closeSession(Request $request, $sessionId)`**: **Updates the `status` of a `ChatSession` to `closed`**. This is the key method that triggers the frontend reset for members.
-   **`getNewMessages(Request $request, $sessionId)`**: Retrieves new messages for an admin's view (used for polling).

### Routes (`routes/web.php`)

```php
// Public Chat Routes
Route::get('/chat', [\App\Http\Controllers\Public\ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [\App\Http\Controllers\Public\ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/messages', [\App\Http\Controllers\Public\ChatController::class, 'getMessages'])->name('chat.messages');

// Admin Chat Management Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... other admin routes ...
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('index');
        Route::get('/{sessionId}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('show');
        Route::post('/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('send');
        Route::post('/{sessionId}/close', [\App\Http\Controllers\Admin\ChatController::class, 'closeSession'])->name('close');
        Route::get('/{sessionId}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getNewMessages'])->name('messages');
    });
});
```

## 4. Frontend (Blade & JavaScript)

### Layout (`resources/views/layouts/app.blade.php`)

-   A floating chat widget is embedded directly into the main application layout.
-   It includes HTML structure for the chat toggle button, chat box (header, messages area, input field), and status indicators.
-   **Inline JavaScript** handles the core chat functionality:
    -   **Session Management**: Stores `session_id` in `localStorage` for persistence across page loads.
    -   **`loadChatHistory()`**: Fetches past messages for the current session.
        -   **Session Closure Handling**: If the `chat.messages` endpoint returns `session_closed: true`, it clears `localStorage`, resets `sessionId`, `userMessageCount`, and displays a new welcome message.
    -   **`sendMessage()`**: Sends user messages to the backend.
        -   Manages typing indicators.
        -   Updates `isConnectedToAdmin` status and `chatStatus` text.
        -   Starts `pollAdminMessages` if connected to admin.
    -   **`pollAdminMessages()`**: Periodically (every 2 seconds) fetches new messages from the backend.
        -   **Session Closure Handling**: If the `chat.messages` endpoint returns `session_closed: true` during polling, it clears the polling interval, `localStorage`, resets session variables, updates `chatStatus`, and displays a "session ended" message followed by a new welcome message.
    -   **`appendMessage(message, sender)`**: Renders messages in the chat box with appropriate styling for user, bot, and admin.
    -   Event listeners for toggling the chat box, sending messages, and handling `Enter` key presses.

### CSS (Inline in `resources/views/layouts/app.blade.php` or `resources/css/app.css`)

-   Provides styling for the chat widget, including:
    -   Fixed positioning for the toggle button and chat box.
    -   Responsive design for the chat box.
    -   Distinct visual styles for user, bot, and admin messages.
    -   Typing indicator animation.
    -   Scrollbar styling for the messages area.

## 5. Key Features

-   **Floating Chat Widget**: Accessible from any page.
-   **Intelligent Bot Responses**: Keyword-based responses for common FAQs.
-   **Automatic Admin Connection**:
    -   After 3 user messages.
    -   After 3 bot failures to understand.
-   **Real-time Admin Polling**: Members receive admin messages in real-time once connected.
-   **Session Persistence**: Chat history is maintained using `localStorage`.
-   **Admin Session Management**: Admins can view and close active chat sessions.
-   **Frontend Session Reset**: When an admin closes a session, the member's chat is cleared, and a new session is automatically initiated.
-   **Typing Indicator**: Visual feedback when the bot or admin is "typing".
-   **Connection Status**: Displays whether the member is chatting with the bot or connected to an admin.

## 6. How to Integrate into Another Program

To integrate this chatbot system into another Laravel application:

1.  **Database Migrations**:
    -   Create `2026_05_14_080530_create_chat_messages_table.php` and `2026_05_14_080532_create_chat_sessions_table.php` migrations.
    -   Run `php artisan migrate`.
2.  **Models**:
    -   Copy `App\Models\ChatSession.php` and `App\Models\ChatMessage.php` to your `app/Models` directory.
    -   Ensure your `User` model has the necessary relationships (e.g., `hasMany(ChatSession::class)`).
3.  **Controllers**:
    -   Copy `App\Http\Controllers\Public\ChatController.php` to `app/Http/Controllers/Public` (or adjust namespace).
    -   Copy `App\Http\Controllers\Admin\ChatController.php` to `app/Http/Controllers/Admin` (or adjust namespace).
    -   Adjust namespaces and any specific dependencies (e.g., `auth()->id()`) to match your application's structure.
4.  **Routes**:
    -   Add the public chat routes and admin chat routes from `routes/web.php` to your application's `routes/web.php` file.
    -   Ensure the admin routes are protected by appropriate middleware (e.g., `auth` and `role:admin`).
5.  **Frontend Integration**:
    -   **HTML Structure**: Copy the `chat-widget-container` div and its contents from `resources/views/layouts/app.blade.php` into your main layout file (e.g., `resources/views/layouts/app.blade.php` or similar).
    -   **JavaScript**: Copy the entire `<script>` block from `resources/views/layouts/app.blade.php` into your main layout file. Ensure CSRF token is correctly passed.
    -   **CSS**: Copy the entire `<style>` block from `resources/views/layouts/app.blade.php` into your main CSS file (e.g., `resources/css/app.css`) or keep it inline if preferred.
    -   **Dependencies**: Ensure you have Bootstrap 5 and Bootstrap Icons included in your project, as the styling and icons rely on them.
6.  **User Roles**:
    -   Ensure your `User` model has an `isAdmin()` method or similar logic to determine if a user has admin privileges, as this is used in the admin routes and potentially in the frontend.
7.  **Testing**:
    -   Thoroughly test the bot responses, admin connection logic, message sending, and session closure from both member and admin perspectives.