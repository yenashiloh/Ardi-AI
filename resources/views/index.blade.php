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
                        <div class="history-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="history-text">What is Paralegal?</div>
                        <div class="history-date">Today</div>
                    </div>

                    <div class="history-item">
                        <div class="history-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="history-text">Product Description Generator</div>
                        <div class="history-date">Yesterday</div>
                    </div>
                    <div class="history-item">
                        <div class="history-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
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
                <div class="user-section">
                    <div class="user-avatar">
                        <div class="avatar-initials">
                            {{ strtoupper(substr($userName, 0, 2)) }}
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ $userName }}</div>
                        <div class="user-email">{{ $userEmail }}</div>
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
                @guest
                    <div class="header-controls">
                        <div class="login">
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    </div>
                @endguest
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

                    {{-- <div class="input-controls">
                        <button class="control-button">
                            <i class="fas fa-paperclip"></i> Attach
                        </button>

                        <button class="control-button">
                            <i class="fas fa-magic"></i> Browse Prompts
                        </button>

                        <div class="character-count">0/3,000</div>
                    </div> --}}
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
</body>

</html>
