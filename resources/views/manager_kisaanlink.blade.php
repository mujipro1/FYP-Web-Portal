@extends('layouts.app')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kisaanlink.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/alert.js') }}"></script>
    <script>
    farm_id = @json($farm_id);
    </script>
</head>

<body>
    <div class="c1">

        <div class="container mb-4">
            <div id="navbar">
                @include('components.navbar')
            </div>
            @yield('content')
        </div>

        <div class='alertDiv fade justify-content-center align-items-center' id="alertDiv"></div>

        @if(Session::get('success') || Session::get('error'))
        @if(Session::get('success'))
        <script>
        showAlert("{{ Session::get('success') }}", 'success', 9000);
        </script>
        @php
        Session::forget('success');
        @endphp
        @endif

        @if(Session::get('error'))
        <script>
        showAlert("{{ Session::get('error') }}", 'error', 9000);
        </script>
        @php
        Session::forget('error');
        @endphp
        @endif
        @endif
        <div class="container-fluid">
            <div class="row section">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-11 offset-md-1" style='padding-right:50px;'>


                    <div class="col-md-11 kisaan-link ">
                    <div class="container-fluid py-4">
                        <div class="d-flex justify-content-start">
                            <div onclick="handleHome()" class="text-light mx-3">Back</div>
                            <h3 class="text-light mb-4">Kisaan Link</h3>
                        </div>
                                    <div class="chat-container shadow-lg rounded-4">
                                        <!-- Sidebar -->
                                        <aside class="users-sidebar p-3">
                                            <input type="text" class="form-control mb-3" placeholder="Search users...">
                                            <div class="users-list">
                                            </div>
                                        </aside>

                                        <!-- Chat Area -->
                                        <main class="chat-area p-3 d-flex flex-column justify-content-start">
                                            <div class="messages-container flex-grow-1 overflow-auto mb-3">
                                                <!-- Messages get rendered here -->
                                            </div>

                                            <div class="d-flex gap-2">
                                                <input type="text" class="form-control message-input"
                                                    placeholder="Type a message...">
                                                <button class="btn btn-success send-button">Send</button>
                                            </div>
                                        </main>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div id="footer">
        @include('components.footer')
    </div>


    </div>

</body>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chats = JSON.parse(@json($chats));
    const allUsers = JSON.parse(@json($allUsers));
    const farm_id = @json($farm_id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const usersList = document.querySelector('.users-list');
    const messagesContainer = document.querySelector('.messages-container');
    const messageInput = document.querySelector('.message-input');
    const sendButton = document.querySelector('.send-button');
    let currentUserId = null;

    let loggedInUser = @json($loggedInUser);

    // Create a header for the chat area
    const chatHeader = document.createElement('div');
    chatHeader.className = 'chat-header d-flex align-items-center p-2 border-bottom mb-3';
    chatHeader.innerHTML = '<div class="text-center w-100 text-muted">Select a user to start chatting</div>';
    messagesContainer.parentNode.insertBefore(chatHeader, messagesContainer);

    // Organize chats by user
    const chatsByUser = {};
    chats.forEach(chat => {
        const userId = chat.user_id;
        if (!chatsByUser[userId]) {
            chatsByUser[userId] = [];
        }
        chatsByUser[userId].push(chat);
    });

    // Filter out the current logged-in user and merge user data with chat metadata
    const enrichedUsers = allUsers
        .filter(user => user.id != loggedInUser) // Exclude the current logged-in user
        .map(user => {
            const userChats = chatsByUser[user.id] || [];
            let lastChat = null;
            if (userChats.length > 0) {
                lastChat = userChats.reduce((a, b) => new Date(a.created_at) > new Date(b.created_at) ? a : b);
            }
            return {
                ...user,
                last_message: lastChat ? lastChat.message : '',
                last_time: lastChat ? lastChat.created_at : null,
            };
        });

    // Sort users by last_time descending
    enrichedUsers.sort((a, b) => {
        const aTime = a.last_time ? new Date(a.last_time).getTime() : 0;
        const bTime = b.last_time ? new Date(b.last_time).getTime() : 0;
        return bTime - aTime;
    });

    // Populate sidebar
    usersList.innerHTML = '';
    enrichedUsers.forEach(user => {
        const userDiv = document.createElement('div');
        userDiv.className = 'user-item';
        userDiv.dataset.userId = user.id;

        const time = user.last_time ? formatTime(new Date(user.last_time)) : '';
        const message = user.last_message ? truncateText(user.last_message, 30) : 'No messages yet';

        userDiv.innerHTML = `
            <img src="{{asset('images/profile.jpg')}}" class="user-avatar" alt="User">
            <div class="user-info">
                <div class="user-name">${user.name}</div>
                <div class="last-message">${message}</div>
            </div>
            <small class="message-time">${time}</small>
        `;
        usersList.appendChild(userDiv);
    });

    // Handle user click
    usersList.querySelectorAll('.user-item').forEach(item => {
        item.addEventListener('click', () => {
            usersList.querySelectorAll('.user-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            currentUserId = item.dataset.userId;

            const selectedUser = enrichedUsers.find(u => u.id == currentUserId);
            
            // Update chat header with selected user info
            chatHeader.innerHTML = `
                <img src="{{asset('images/profile.jpg')}}" class="user-avatar me-2" alt="Selected User">
                <div>
                    <div class="user-name mb-1 fw-semibold">${selectedUser.name}</div>
                    <small class="user-status text-success">Online</small>
                </div>
            `;
            
            loadUserMessages(currentUserId);
        });
    });

    function loadUserMessages(userId) {
        messagesContainer.innerHTML = '';
        const messages = chatsByUser[userId] || [];

        const sortedMessages = [...messages].sort((a, b) =>
            new Date(a.created_at) - new Date(b.created_at)
        );

        sortedMessages.forEach(chat => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${chat.from === 1 ? 'sent' : 'received'}`;

            messageDiv.innerHTML = `
                <div class="message-content">
                    ${chat.message}
                    <span class="message-time">${formatTime(new Date(chat.created_at))}</span>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message || !currentUserId) return;

        const timestamp = new Date().toISOString();

        const newChat = {
            message: message,
            created_at: timestamp,
            from: 1,
            user_id: parseInt(currentUserId),
        };

        // Update frontend immediately
        if (!chatsByUser[currentUserId]) chatsByUser[currentUserId] = [];
        chatsByUser[currentUserId].push(newChat);
        updateUIAfterSend(message, timestamp);

        fetch('/kisaan-link/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                message: message,
                to: parseInt(currentUserId),
                from: farm_id,
            })
        }).catch(err => {
            console.error('Message sending error:', err);
            showAlert("Message failed to send", 'error', 5000);
        });
    }

    function updateUIAfterSend(message, timestamp) {
        const div = document.createElement('div');
        div.className = 'message sent';
        div.innerHTML = `
            <div class="message-content">
                ${message}
                <span class="message-time">${formatTime(new Date(timestamp))}</span>
            </div>
        `;
        messagesContainer.appendChild(div);
        messageInput.value = '';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function updateUIAfterReceive(message, timestamp) {
        const div = document.createElement('div');
        div.className = 'message received';
        div.innerHTML = `
            <div class="message-content">
                ${message}
                <span class="message-time">${formatTime(new Date(timestamp))}</span>
            </div>
        `;
        messagesContainer.appendChild(div);
        messageInput.value = '';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function formatTime(date) {
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function truncateText(text, maxLength) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage();
    });

    // Auto-select first user
    const firstUser = usersList.querySelector('.user-item');
    if (firstUser) firstUser.click();

    // Refresh messages every 3 seconds
   // Refresh messages every 3 seconds
    setInterval(() => {
        if (!currentUserId) return;

        fetch(`/kisaan-link/get-message/${farm_id}`)
            .then(res => res.json())
            .then(data => {
                const incomingChats = data || [];
                let hasNewMessages = false;
                
                incomingChats.forEach(chat => {
                    if (chat.from == currentUserId) {
                        const chatData = {
                            message: chat.message,
                            created_at: chat.created_at,
                            from: 0, // Mark as received message (0 = received, 1 = sent)
                            user_id: currentUserId
                        };

                        if (!chatsByUser[currentUserId]) {
                            chatsByUser[currentUserId] = [];
                        }
                        
                        // Check if chatData already exists based on message content and timestamp
                        if (!chatsByUser[currentUserId].some(existingChat => 
                            existingChat.message === chatData.message && 
                            existingChat.created_at === chatData.created_at
                        )) {
                            chatsByUser[currentUserId].push(chatData);
                            
                            // If currently viewing this user, update UI immediately
                            if (currentUserId == chat.from) {
                                updateUIAfterReceive(chatData.message, chatData.created_at);
                                hasNewMessages = true;
                            }
                        }
                    }
                    else{

                        const messageTime = new Date(chat.created_at);
                        const currentTime = new Date();
                        const timeDifferenceMinutes = (currentTime - messageTime) / (1000 * 60);
                    
                        if (timeDifferenceMinutes <= 10) {
                            updateNewMessage(chat.from, chat.message, chat.created_at);
                        }

                    }
                });
                
                // Only reload all messages if we didn't individually add them
                if (hasNewMessages && !incomingChats.length) {
                    loadUserMessages(currentUserId);
                }
            })
            .catch(err => console.error('Fetching error:', err));
    }, 3000);


    function updateNewMessage(incomingUser, incomingMessage, incomingTime){
        usersList.innerHTML = '';
        enrichedUsers.forEach(user => {
            const userDiv = document.createElement('div');
            userDiv.className = `user-item  ${incomingUser == user.id ? 'highlighted' : ''}`;
            userDiv.dataset.userId = user.id;

            const time = user.last_time ? formatTime(new Date(user.last_time)) : '';
            const message = user.last_message ? truncateText(user.last_message, 30) : 'No messages yet';

            userDiv.innerHTML = `
                <img src="{{asset('images/profile.jpg')}}" class="user-avatar" alt="User">
                <div class="user-info">
                    <div class="user-name">${user.name}</div>
                    <div class="last-message">${incomingUser == user.id ?  incomingMessage : message}</div>
                </div>
                <small class="message-time">${incomingUser == user.id ?  formatTime(new Date(incomingTime)) : time}</small>
            `;
            usersList.appendChild(userDiv);
        });

        // Handle user click
        usersList.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', () => {
                usersList.querySelectorAll('.user-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                currentUserId = item.dataset.userId;

                const selectedUser = enrichedUsers.find(u => u.id == currentUserId);
                
                // Update chat header with selected user info
                chatHeader.innerHTML = `
                    <img src="{{asset('images/profile.jpg')}}" class="user-avatar me-2" alt="Selected User">
                    <div>
                        <div class="user-name mb-1 fw-semibold">${selectedUser.name}</div>
                        <small class="user-status text-success">Online</small>
                    </div>
                `;
                
                loadUserMessages(currentUserId);
            });
        });
    }


});
function handleHome(){
        window.location.href = "{{ route('manager.farmdetails' , ['farm_id' => $farm_id] )}}"

    }
</script>

</html>