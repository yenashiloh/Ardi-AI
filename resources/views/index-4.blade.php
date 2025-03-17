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
            {{--             
            <div class="sidebar-toggle">
                <i class="fas fa-chevron-left"></i>
            </div> --}}
             <div class="search-bar">
                <input type="text" placeholder="Search chats...">
            </div>
            
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
                    <div class="history-text">Social Media Strategy Plan</div>
                    <div class="history-date">Mar 10</div>
                </div>
                
                <div class="history-item">
                    <div class="history-icon">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="history-text">Email Campaign Ideas</div>
                    <div class="history-date">Mar 8</div>
                </div>
                
                <div class="history-item">
                    <div class="history-icon">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="history-text">Website Copy Suggestions</div>
                    <div class="history-date">Mar 5</div>
                </div>
            </div>
            
            <div class="theme-switch mt-auto">
                <div class="theme-option active">
                  <i class="fa-solid fa-sun" style="font-size: 12px; margin-right: 3px;"></i> Light
                </div>
                <div class="theme-option">
                  <i class="fa-solid fa-moon" style="font-size: 12px; margin-right: 3px;"></i> Dark
                </div>
              </div>

            <div class="user-section">
                <div class="user-avatar">
                    <img src="../../assets/images/photo3.jpg" alt="User avatar">
                </div>
                <div class="user-info">
                    <div class="user-name">John Doe</div>
                    <div class="user-email">johndoe@gmail.com</div>
                </div>
                {{-- <div class="user-settings">
                    <i class="fas fa-cog"></i>
                </div> --}}
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
                    
                </div>
            </div>
            
            <div class="messages">
                <div class="message">
                    <div class="message-content">
                        <div class="message-prompt">
                            Lorem ipsum odor amet, consectetuer adipiscing elit. Pharetra lectus primis senectus a sollicitu
                        </div>
                    </div>
                </div>
                
                <div class="message">
                    <div class="message-avatar">
                        <img src="../assets/images/Ardi-Logo.svg" alt="AI avatar">
                    </div>
                    <div class="message-content">
                        <div class="message-result">
                            <div class="headlines">
                                <div class="headline-item">
                                    <div class="headline-text">Lorem ipsum odor amet, consectetuer adipiscing elit. Pharetra lectus primis senectus a sollicitudin rutrum inceptos sagittis. Aliquet nullam rhoncus, tellus porta finibus cursus leo odio quisque. Mi cubilia lacus montes; curabitur eu lacus. Sed ante proin ut netus massa lorem viverra litora consequat. Augue aenean ornare enim cras ridiculus nascetur cursus tortor. Lobortis pharetra potenti consequat laoreet netus interdum pretium eget.
                                     </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="message-actions">
                            <button class="action-button">
                                <i class="far fa-copy"></i> Copy
                            </button>
                            
                            <button class="action-button">
                                <i class="fas fa-share"></i> Share
                            </button>
                        </div>
                    </div>
                </div>

                <div class="message">
                    <div class="message-content">
                        <div class="message-prompt">
                            Lorem ipsum odor amet, consectetuer adipiscing elit. Pharetra lectus primis senectus a sollicitu Lorem ipsum odor amet, consectetuer adipiscing elit. Pharetra lectus primis senectus a sollicitu
                        </div>
                    </div>
                </div>            
                
                {{-- <div class="regenerate-button">
                    <button class="regenerate">
                        <i class="fas fa-refresh"></i> Regenerate
                    </button>
                </div> --}}
            </div>
            
            <div class="input-area">
                <div class="message-input-container">
                    <textarea class="message-input" placeholder="Ask a question..."></textarea>

                    <div class="send-button">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                
                <div class="input-controls">
                    <button class="control-button">
                        <i class="fas fa-paperclip"></i> Attach
                    </button>
                    
                    {{-- <button class="control-button">
                        <i class="fas fa-microphone"></i> Voice Message
                    </button> --}}
                    
                    <button class="control-button">
                        <i class="fas fa-magic"></i> Browse Prompts
                    </button>
                    
                    <div class="character-count">0/3,000</div>
                </div> 
                
                <div class="footer-text">
                    Ardi AI can make mistakes. Please verify important information.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="assets/js/chatbot.js"></script>
