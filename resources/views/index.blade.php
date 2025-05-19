<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ardi AI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon" />
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>

<body>
    <div class="sidebar-overlay"></div>

    <div class="main-content">
        <!-- Sidebar with chat history -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <img src="../assets/images/Ardi-Logo.svg" alt="Ardi Logo">
                </div>
                <span>Ardi AI</span>
            </div>

            <button class="new-chat-btn mt-1">
                <i class="fas fa-plus"></i> <span>New Chat</span>
            </button>

            @auth
                <div class="chat-history">
                    <div class="history-item active">
                        <div class="history-text">What is Paralegal?</div>
                        <div class="history-date">Today</div>
                    </div>

                    <div class="history-item">
                        <div class="history-text">Product Description Generator</div>
                        <div class="history-date">Yesterday</div>
                    </div>
                    <div class="history-item">
                        <div class="history-text">Product Description Generator</div>
                        <div class="history-date">Yesterday</div>
                    </div>
                </div>
            @endauth

            <div class="theme-switch mt-auto">
                <div class="theme-option active">
                    <i class="fa-solid fa-sun" style="font-size: 12px; margin-right: 3px;"></i> Light
                </div>
                <div class="theme-option">
                    <i class="fa-solid fa-moon" style="font-size: 12px; margin-right: 3px;"></i> Dark
                </div>
            </div>
            @auth
                <div class="user-section" id="open-user-modal">

                    <div class="user-avatar">
                        <div class="avatar-initials">
                            {{ $userInitial }}
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ $userFirstName }} {{ $userLastName }}</div>
                        <div class="user-email">{{ $userEmail }}</div>
                    </div>
                </div>
            @endauth

            @auth
                <!-- User Settings Modal -->
                <div class="user-modal" id="user-modal">
                    <div class="modal-overlay"></div>
                    <div class="modal-container">
                        <div class="modal-header">
                            <h3>User Settings</h3>
                            <button class="close-modal"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-columns">
                                <!-- Left Column -->
                                <div class="modal-column">
                                    <div class="form-group">
                                        <label for="user-first-name">First Name</label>
                                        <input type="text" id="user-first-name" class="modal-input"
                                            placeholder="Enter first name" value="{{ $userFirstName }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="user-last-name">Last Name</label>
                                        <input type="text" id="user-last-name" class="modal-input"
                                            placeholder="Enter last name" value="{{ $userLastName }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email-address">Email Address</label>
                                        <input type="email" id="user-email-address" class="modal-input"
                                            placeholder="Enter email address" value="{{ $userEmail }}" disabled>
                                    </div>
                                </div>
                                <!-- Right Column -->
                                <div class="modal-column">
                                    <div class="form-group">
                                        <label for="user-current-password">Current Password</label>
                                        <input type="password" id="user-current-password" class="modal-input"
                                            placeholder="Enter current password">
                                    </div>
                                    <div class="form-group">
                                        <label for="user-password">New Password</label>
                                        <input type="password" id="user-password" class="modal-input"
                                            placeholder="Enter new password">
                                    </div>
                                    <div class="form-group">
                                        <label for="user-password-confirm">Confirm New Password</label>
                                        <input type="password" id="user-password-confirm" class="modal-input"
                                            placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-button cancel-button" id="close-modal">Cancel</button>
                            <button class="modal-button save-button">Save Changes</button>
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Chat area -->
        <div class="chat-area">
            <div class="chat-header">
                <div style="display: flex; align-items: center;">
                    <div class="sidebar-toggle-mobile">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
                <div class="header-controls">
                    @guest
                        <div class="login">
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    @else
                        <div class="login">
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    @endguest
                </div>
            </div>

            <div class="messages">
                <div class="welcome-container">
                    <div class="welcome-header">
                        <div class="logo-icon logo-heading">
                            <img src="../assets/images/Ardi-Logo.svg" alt="Ardi Logo">
                        </div>
                        <h1 class="welcome-heading">Hello, I'm Ardi AI</h1>
                    </div>
                    <p class="welcome-subheading">Ask me anything about legal case management</p>
                </div>

                @guest
                    <div class="card-grid">
                        @foreach ($responses->take(6) as $response)
                            <div class="info-card" data-id="{{ $response->id }}">
                                <p>{{ $response->question }}</p>
                            </div>
                        @endforeach
                    </div>
                @endguest
            </div>

            @auth
                <div class="input-area">
                    <div class="message-input-container">
                        <textarea class="message-input" placeholder="Ask a question..."></textarea>

                        <div class="send-button">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                @endauth
                <div class="footer-text">
                    Ardi AI can make mistakes. Please verify important information.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="assets/js/chatbot.js"></script>
    <script src="assets/js/ai-response.js"></script>
    <script src="assets/js/user-settings.js"></script>
    <script src="assets/js/prevent-back-history.js"></script>
    {{-- <script src="assets/js/python-response.js"></script> --}}
    <!-- Add this before the closing </body> tag -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const messageInput = document.querySelector('.message-input');
            const sendButton = document.querySelector('.send-button');
            const messagesContainer = document.querySelector('.messages');
            const welcomeContainer = document.querySelector('.welcome-container');
            const cardGrid = document.querySelector('.card-grid');

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Function to create and add user message to chat
            function addUserMessage(text) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-prompt">
                    ${escapeHtml(text)}
                </div>
            </div>
        `;
                messagesContainer.appendChild(messageDiv);

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Function to create and add AI response to chat
            function addAIMessage(text) {
                // Parse the Markdown to HTML
                const parsedText = marked.parse(text);

                const messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                messageDiv.innerHTML = `
        <div class="message-avatar">
            <img src="../assets/images/Ardi-Logo.svg" alt="AI avatar">
        </div>
        <div class="message-content">
            <div class="message-result">
                <div class="headlines">
                    <div class="headline-item">
                        <div class="headline-text markdown-content">${parsedText}</div>
                    </div>
                </div>
            </div>
            <div class="message-actions">
                <button class="action-button copy-button">
                    <i class="far fa-copy"></i> Copy
                </button>
                <button class="action-button share-button">
                    <i class="fas fa-share"></i> Share
                </button>
            </div>
        </div>
    `;
                messagesContainer.appendChild(messageDiv);

                // Attach event listener to the copy button
                const copyButton = messageDiv.querySelector('.copy-button');
                if (copyButton) {
                    copyButton.addEventListener('click', function() {
                        // For the copy functionality, use the original markdown text
                        // rather than the parsed HTML
                        copyToClipboard(text, copyButton);
                    });
                }

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Function to create and add loading indicator
            function addLoadingIndicator() {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message loading-message';
                messageDiv.innerHTML = `
            <div class="message-avatar">
                <img src="../assets/images/Ardi-Logo.svg" alt="AI avatar">
            </div>
            <div class="message-content">
                <div class="message-result">
                    <div class="headlines">
                        <div class="headline-item">
                            <div class="headline-text">
                                <span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                messagesContainer.appendChild(messageDiv);

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                return messageDiv;
            }

            // Helper function to escape HTML
            function escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Function to copy text to clipboard
            function copyToClipboard(text, button) {
                navigator.clipboard.writeText(text).then(function() {
                    // Store original button content
                    const originalContent = button.innerHTML;

                    // Change button text to 'Copied!'
                    button.innerHTML = '<i class="fas fa-check"></i> Copied!';

                    // Restore original content after 2 seconds
                    setTimeout(function() {
                        button.innerHTML = originalContent;
                    }, 2000);
                }, function() {
                    // If copying fails
                    console.error('Failed to copy text');
                });
            }

            // Function to update loading message with status
            function updateLoadingMessage(loadingMessage, statusText) {
                const textElement = loadingMessage.querySelector('.headline-text');
                if (textElement) {
                    textElement.innerHTML = `
                <span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
                <div class="status-text">${statusText}</div>
            `;
                }
            }

            // Function to handle sending message with asynchronous processing
            async function sendMessage() {
                const query = messageInput.value.trim();

                // Don't send empty messages
                if (!query) {
                    return;
                }

                // Add user message to chat
                addUserMessage(query);

                // Clear input field
                messageInput.value = '';

                // Hide welcome container and cards if visible
                if (welcomeContainer) {
                    welcomeContainer.style.display = 'none';
                }
                if (cardGrid) {
                    cardGrid.style.display = 'none';
                }

                // Add loading indicator
                const loadingMessage = addLoadingIndicator();

                try {
                    console.log("Submitting query:", query);

                    // Submit the query for processing
                    const submitResponse = await fetch('/knowledge-base/submit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            query: query
                        })
                    });

                    const submitData = await submitResponse.json();

                    if (!submitResponse.ok || !submitData.success) {
                        throw new Error(submitData.error || 'Failed to submit query');
                    }

                    const queryId = submitData.query_id;
                    console.log("Query submitted with ID:", queryId);

                    // Poll for the status of the query
                    let completed = false;
                    let attempts = 0;
                    const maxAttempts = 60; // Poll for up to 60 attempts (5 minutes)

                    while (!completed && attempts < maxAttempts) {
                        await new Promise(resolve => setTimeout(resolve, 3000)); // Wait 3 seconds between polls

                        attempts++;
                        console.log(`Checking status (attempt ${attempts})...`);

                        const statusResponse = await fetch(`/knowledge-base/status/${queryId}`);

                        if (!statusResponse.ok) {
                            throw new Error('Failed to check query status');
                        }

                        const statusData = await statusResponse.json();
                        console.log("Status data:", statusData);

                        if (statusData.status === 'complete') {
                            // Remove loading indicator
                            loadingMessage.remove();

                            // Add AI response
                            addAIMessage(statusData.response);

                            completed = true;
                        } else if (statusData.status === 'failed') {
                            // Remove loading indicator
                            loadingMessage.remove();

                            // Show error message
                            addAIMessage(`Error: ${statusData.error}`);

                            completed = true;
                        } else {
                            // Update loading message with status
                            updateLoadingMessage(loadingMessage, statusData.message ||
                                "Processing your query...");
                        }
                    }

                    // If we're at the maximum number of attempts and not completed, show timeout message
                    if (attempts >= maxAttempts && !completed) {
                        loadingMessage.remove();
                        addAIMessage('The query is taking longer than expected. Please try again later.');
                    }
                } catch (error) {
                    console.error('Error:', error);

                    // Remove loading indicator
                    loadingMessage.remove();

                    // Show error message
                    addAIMessage(
                        `Sorry, there was an error connecting to the knowledge base: ${error.message}`);
                }
            }

            // Event listeners
            if (sendButton) {
                sendButton.addEventListener('click', sendMessage);
            }

            if (messageInput) {
                messageInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();
                        sendMessage();
                    }
                });
            }
        });
    </script>
</body>

</html>
