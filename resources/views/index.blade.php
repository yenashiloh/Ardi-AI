<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ardi AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
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
  
            <div class="theme-switch mt-auto">
                <div class="theme-option active">
                  <i class="fa-solid fa-sun" style="font-size: 12px; margin-right: 3px;"></i> Light
                </div>
                <div class="theme-option">
                  <i class="fa-solid fa-moon" style="font-size: 12px; margin-right: 3px;"></i> Dark
                </div>
              </div>
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
                    <div class="login">
                        <a href="{{ route('login') }}">Login</a>
                    </div> 
                    <div class="sign-up">
                        <a href="{{ route('sign-up') }}">Sign Up</a>
                    </div> 
                </div>
            </div>
            
            <div class="messages">
                <div class="welcome-container">
                    <div class="welcome-header">
                        <div class="logo-icon logo-heading">
                            <img src="../assets/images/Ardi-Logo.svg" alt="Ardi Logo">
                        </div>
                        <h1 class="welcome-heading">Hello, I'm Ardi</h1>
                    </div>
                    <p class="welcome-subheading">Ask me anything about legal case management</p>
                </div>
                
                <div class="card-grid">
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                    <div class="info-card">
                        <p>Lorem ipsum dolor amet, consectetuer adipiscing elit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="assets/js/chatbot.js"></script>
