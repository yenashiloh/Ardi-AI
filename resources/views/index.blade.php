<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/Ardi-Logo.svg" type="image/x-icon"/>
    <script>
        (function() {
          const savedTheme = localStorage.getItem("themeColor");
          if (savedTheme === "light_mode") {
            document.documentElement.classList.add("light_mode");
            document.body.classList.add("light_mode");
          }
        })();
      </script>
      
      <style>
    .user-profile-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        position: relative;
        background-color: #ffffff;
        margin: 10% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
        animation: slideDown 0.3s;
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-header h2 {
        color: #333;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .close-modal-btn {
        background: transparent;
        border: none;
        font-size: 24px;
        color: #888;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .close-modal-btn:hover {
        color: #333;
    }

    .modal-tabs {
        display: flex;
        background-color: #f9f9f9;
        padding: 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .tab-btn {
        flex: 1;
        padding: 12px 0;
        background: transparent;
        border: none;
        font-size: 1rem;
        color: #555;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        transition: all 0.2s ease;
    }

    .tab-btn.active {
        color: #000;
        background-color: #fff;
    }

    .modal-body {
        padding: 16px 20px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .input-group {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
    }

    .input-group:last-child {
        margin-bottom: 0;
    }

    .input-group label {
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .input-value {
        display: flex;
        align-items: center;
        color: #555;
    }

    .input-value span {
        margin-right: 8px;
    }

    .google-icon {
        width: 16px;
        height: 16px;
        margin-left: 6px;
    }

    .delete-btn {
        background-color: #f44336;
        color: white;
        border: none;

        width: 90px;
        height: 32px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s;
    }

    .delete-btn:hover {
        background-color: #d32f2f;
    }

    .modal-tabs {
        display: flex;
        background-color: #f5f5f5;
        border-bottom: 1px solid #eee;
        padding: 0;
    }

    .tab-btn {
        flex: 1;
        text-align: center;
        padding: 12px 0;
        border: none;
        border-bottom: 2px solid transparent;
        background: none;
        font-weight: 500;
        color: #555;
        transition: all 0.2s ease;
    }

    .tab-btn.active {
        border-bottom-color: #555;
        color: #000;
    }

    .input-value span {
        color: #555;
    }
    </style>
    <title>Ardi Ask AI</title>
</head>

<body>

    <nav class="navbar">
        <div class="navbar__left">
            <!-- Toggle button (Chevrons Right) -->
            <button class="navbar__menu-btn" id="toggleSidebar">
                <i class='bx bx-chevrons-right'></i>
                <span class="tooltip-text">Open Sidebar</span>
            </button>
            
            <button class="navbar__new-chat-btn">
                <i class='bx bx-plus'></i>
                <span class="tooltip-text">New Chat</span>
            </button>
        </div>
        
        <div class="navbar__right">
            <a href="{{ route('login') }}" class="navbar__login-btn">
                Login
            </a>
            

            <a href="{{ route('sign-up')}}" class="navbar__sign-up-btn">
                Sign Up
            </a>
            
            <!-- Dark Mode -->
            <button class="navbar__button" id="themeToggler">
                <i class='bx bx-sun'></i>
            </button>
        </div>
    
        <!-- Sidebar-->
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
                        <i class="bx bx-log-out"></i>

                        <span>Log out</span>
                    </div>
                    <div class="sidebar__user">
                        <div class="sidebar__user-avatar">A</div>
                        <span>Attorney@example.com</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div id="userProfileModal" class="user-profile-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Profile</h2>
                <button id="closeModalBtn" class="close-modal-btn">&times;</button>
            </div>
            
            <div class="modal-body">
                <div class="tab-content active" >
                    <div class="input-group">
                        <label for="name">Name</label>
                        <div class="input-value">
                            <span>Shiloh</span>
                            <img src="https://cdn-icons-png.flaticon.com/512/2875/2875331.png" alt="Google" class="google-icon">
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="email">Email address</label>
                        <div class="input-value">
                            <span>shilo****enio21@gmail.com</span>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="phone">Phone number</label>
                        <div class="input-value">
                            <span>-</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="terms">Terms of Use</label>
                        <div class="input-value">
                            <span>View</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="privacy">Privacy Policy</label>
                        <div class="input-value">
                            <span>View</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="delete">Delete account</label>
                        <div class="input-value">
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="profile">
                    <!-- Profile tab content here -->
                    <p>Profile settings will appear here.</p>
                </div>
            </div>
        </div>
    </div>
    
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
                <button class="prompt__form-button">
                    <i class='bx bx-up-arrow-alt'></i>
                </button>
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
<script>
    // Add this to your sidebar.js or create a new script file for the modal functionality

document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('userProfileModal');
    const closeBtn = document.getElementById('closeModalBtn');
    const userAvatar = document.querySelector('.sidebar__user-avatar');
    const userSection = document.querySelector('.sidebar__user');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Function to open the modal
    function openModal() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }

    // Function to close the modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Allow scrolling again
    }

    // Event listeners for opening and closing the modal
    userAvatar.addEventListener('click', openModal);
    userSection.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Tab functionality
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
});
</script>
</html>