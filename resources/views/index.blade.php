<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    
    <title>Ardi Ask AI</title>
</head>

<body>

    <nav class="navbar">
        <div class="navbar__left">
            <!-- Toggle button (Chevrons Right) -->
            <button class="navbar__menu-btn " id="toggleSidebar">
                <i class='bx bx-chevrons-right'></i>
                <span class="tooltip-text">Open Sidebar</span>
            </button>
            
            <button class="navbar__new-chat-btn">
                <i class='bx bx-plus'></i>
                <span class="tooltip-text">New Chat</span>
            </button>
        </div>
        
        {{-- <button class="navbar__login-btn">
            Login
        </button> --}}

        <!-- Dark mode button on the right -->
        <button class="navbar__button" id="themeToggler">
            <i class='bx bx-sun'></i>
        </button>
    
        <!-- Sidebar (remains outside the top row) -->
        <div class="sidebar">
            <div class="sidebar__logo">
                <span class="sidebar__title">Ardi Ask AI</span>
                <img src="assets/images/Ardi-Logo-White.svg" alt="Ardi Logo">
            </div>
            <div class="sidebar__header">
                <button class="sidebar__new-chat">
                    <i class='bx bx-plus'></i>
                    <span>New chat</span>
                </button>
                <button class="sidebar__close-btn" id="closeSidebar">
                    <i class='bx bx-chevrons-left'></i>
                    <span class="tooltip-text">Close Sidebar</span>

                </button>
            </div>
        
            <div class="sidebar__content">
                <div class="sidebar__conversations">
                    <h3 class="sidebar__section-title">Recent conversations</h3>
                    
                    <div class="sidebar__chat-list">
                        <div class="sidebar__chat-item active">
                            <i class='bx bx-message'></i>
                            <span>Legal case workflow optimization</span>
                        </div>
                        <div class="sidebar__chat-item">
                            <i class='bx bx-message'></i>
                            <span>Document automation options</span>
                        </div>
                        <div class="sidebar__chat-item">
                            <i class='bx bx-message'></i>
                            <span>Client intake best practices</span>
                        </div>
                    </div>
                </div>
        
                <div class="sidebar__footer">
                    <div class="sidebar__footer-item">
                        <i class='bx bx-trash'></i>
                        <span>Clear conversations</span>
                    </div>
                    <div class="sidebar__footer-item">
                        <i class='bx bx-cog'></i>
                        <span>Settings</span>
                    </div>
                    <div class="sidebar__user">
                        <div class="sidebar__user-avatar">A</div>
                        <span>Attorney@example.com</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Add the overlay for mobile outside the navbar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
    <header class="header">
        <div class="header__title">
            <img src="assets/images/Ardi-Logo.svg" alt="">
            <h1>Hello, I'm Ardi</h1>
            <h2>Ask me anything about legal case management</h2>
        </div>
        <div class="suggests">
            <div class="suggests__item">
                <p class="suggests__item-text">
                    Lorem ipsum odor amet, consectetuer adipiscing elit. 
                </p>
                <!-- <div class="suggests__item-icon">
                    <i class='bx bx-stopwatch'></i>
                </div> -->
            </div>
            <div class="suggests__item">
                <p class="suggests__item-text">
                    Lorem ipsum odor amet, consectetuer adipiscing elit. 
                </p>
                <!-- <div class="suggests__item-icon">
                    <i class='bx bx-edit-alt'></i>
                </div> -->
            </div>
            <div class="suggests__item">
                <p class="suggests__item-text">
                    Lorem ipsum odor amet, consectetuer adipiscing elit. 
                </p>
                <!-- <div class="suggests__item-icon">
                    <i class='bx bx-compass'></i>
                </div> -->
            </div>
            <div class="suggests__item">
                <p class="suggests__item-text">
                    Lorem ipsum odor amet, consectetuer adipiscing elit. 
                </p>
                <!-- <div class="suggests__item-icon">
                    <i class='bx bx-wrench'></i>
                </div> -->
            </div>
        </div>
    </div>
    </header>

    <section class="chats"></section>

    
    <section class="prompt">
        <div class="main-content">
        <form action="#" class="prompt__form" novalidate>
            <div class="prompt__input-wrapper">
                <input type="text" placeholder="Ask anything.." class="prompt__form-input" required>
                <button class="prompt__form-button" >
                    <i class='bx bx-send'></i>
                </button>
                <!-- <button class="prompt__form-button" id="deleteButton">
                    <i class='bx bx-send'></i>
                </button> -->
            </div>
        </form>
        <p class="prompt__disclaim">
            Ardi AI can make mistakes. Please verify important information.
        </p>
    </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/sidebar.js"></script>
</body>

</html>