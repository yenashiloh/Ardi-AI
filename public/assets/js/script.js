document.addEventListener('DOMContentLoaded', function() {
    // Reference elements
    const themeToggleButton = document.getElementById("themeToggler");
    const ardiLogo = document.querySelector(".header__title img");
    
    // Check localStorage for saved theme preference
    const savedTheme = localStorage.getItem("themeColor") || "dark_mode"; // Default to dark mode
    
    // Ensure body and html match the saved theme
    const isLightTheme = savedTheme === "light_mode";
    document.documentElement.classList.toggle("light_mode", isLightTheme);
    document.body.classList.toggle("light_mode", isLightTheme);
    
    // Update theme toggle button icon
    if (themeToggleButton) {
        themeToggleButton.innerHTML = isLightTheme ? 
            '<i class="bx bx-moon"></i>' : 
            '<i class="bx bx-sun"></i>';
    }
    
    // Update Ardi logo based on theme
    if (ardiLogo) {
        ardiLogo.src = isLightTheme ? 
            "assets/images/Ardi-Logo.svg" : 
            "assets/images/Ardi-Logo-White.svg";
    }
    
    // Call loadSavedChatHistory after theme is applied
    loadSavedChatHistory();
});

// Theme toggle handler - ensure it's defined after the elements exist
const themeToggleButton = document.getElementById("themeToggler");
if (themeToggleButton) {
    themeToggleButton.addEventListener('click', () => {
        // Toggle theme on both html and body
        const isLightTheme = document.body.classList.toggle("light_mode");
        document.documentElement.classList.toggle("light_mode", isLightTheme);
        
        // Save theme preference
        localStorage.setItem("themeColor", isLightTheme ? "light_mode" : "dark_mode");

        // Update icon based on theme
        const newIconClass = isLightTheme ? "bx bx-moon" : "bx bx-sun";
        themeToggleButton.querySelector("i").className = newIconClass;
        
        // Update Ardi logo based on theme
        const ardiLogo = document.querySelector(".header__title img");
        if (ardiLogo) {
            ardiLogo.src = isLightTheme ? 
                "assets/images/Ardi-Logo.svg" : 
                "assets/images/Ardi-Logo-White.svg";
        }
    });
}

// State variables
let currentUserMessage = null;
let isGeneratingResponse = false;

const loadSavedChatHistory = () => {
    const savedConversations = JSON.parse(localStorage.getItem("saved-api-chats")) || [];
    const chatHistoryContainer = document.querySelector('.chats'); // Make sure this selector matches your HTML
    
    // Clear container
    if (chatHistoryContainer) {
        chatHistoryContainer.innerHTML = '';

        // Iterate through saved chat history and display messages
        savedConversations.forEach(conversation => {
            // Your existing chat history loading code
        });

        document.body.classList.toggle("hide-header", savedConversations.length > 0);
    }
};