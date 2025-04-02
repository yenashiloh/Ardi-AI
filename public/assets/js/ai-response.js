document.addEventListener('DOMContentLoaded', function() {
    // Select all info cards
    const infoCards = document.querySelectorAll('.info-card');
    console.log('Found info cards:', infoCards.length);
    
    // Create message display area if it doesn't exist
    // Let's create a div after the card-grid
    const cardGrid = document.querySelector('.card-grid');
    const welcomeContainer = document.querySelector('.welcome-container');
    
    if (cardGrid && !document.querySelector('#message-display')) {
        const messageDisplay = document.createElement('div');
        messageDisplay.id = 'message-display';
        messageDisplay.style.display = 'none'; // Hidden initially
        
        // Create the structure for our messages with fixed layout
        messageDisplay.innerHTML = `
            <div class="chat-container" style="display: flex; flex-direction: column; max-width: 800px; margin: 0 auto;">
                <!-- User prompt -->
                <div class="prompt-container" style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
                    <div class="message-prompt" style="white-space: pre-line; line-height: 1.5; text-align: left;"></div>
                </div>
                
                <!-- AI response -->
                <div class="message" style="display: flex; margin-bottom: 24px;">
                    <div class="message-avatar">
                        <img src="../assets/images/Ardi-Logo.svg" alt="AI avatar">
                    </div>
                    <div class="message-content">
                        <div class="message-result">
                            <div class="headlines">
                                <div class="headline-item">
                                    <div class="headline-text" style="white-space: pre-line; line-height: 1.5;">
                                        <!-- Response will be loaded here -->
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
            </div>
        `;
        
        // Insert after the card grid
        cardGrid.parentNode.insertBefore(messageDisplay, cardGrid.nextSibling);
    }
    
    // Add click event listener to each card
    infoCards.forEach(card => {
        card.style.cursor = 'pointer';
        
        card.addEventListener('click', function() {
            console.log('Card clicked!');
            
            // Get the question from the card
            const question = this.querySelector('p').textContent;
            
            // Get MongoDB ObjectId from the card
            const objectId = this.getAttribute('data-id');
            console.log('ObjectId:', objectId);
            
            // Hide the welcome container
            if (welcomeContainer) {
                welcomeContainer.style.display = 'none';
            }
            
            // Hide the card grid
            if (cardGrid) {
                cardGrid.style.display = 'none';
            }
            
            // Show the message display
            const messageDisplay = document.querySelector('#message-display');
            if (messageDisplay) {
                messageDisplay.style.display = 'block';
            }
            
            // Set the question with proper paragraph spacing
            const promptElement = document.querySelector('.message-prompt');
            if (promptElement) {
                promptElement.textContent = question;
            }
            
            // Show original loading dots in response area
            const headlineElement = document.querySelector('.headline-text');
            if (headlineElement) {
                headlineElement.innerHTML = '<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>';
            }
            
            // Make the AJAX request using web route
            fetch('/responses/' + objectId, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Wait a bit to show the loading animation
                setTimeout(() => {
                    if (headlineElement) {
                        // Clear the loading indicator
                        headlineElement.textContent = '';
                        
                        // Get the response text
                        const responseText = data.response || 'No response available';
                        
                        // Type out the response with a faster typing effect
                        typeResponse(responseText, headlineElement);
                    }
                }, 800);
            })
            .catch(error => {
                console.error('Error:', error);
                if (headlineElement) {
                    headlineElement.textContent = "Sorry, we couldn't load the response. Please try again.";
                }
            });
        });
    });
    
    // Function to create a typing effect
    function typeResponse(text, element) {
        let i = 0;
        const speed = 5; // Fast typing speed
        const chunkSize = 3; // Process multiple characters at once for faster effect
        
        function type() {
            for (let j = 0; j < chunkSize && i < text.length; j++) {
                // Preserve paragraph breaks by checking for newlines
                if (i < text.length - 1 && text.charAt(i) === '\n' && text.charAt(i+1) === '\n') {
                    element.innerHTML += '<br><br>';
                    i += 2;
                } else {
                    element.innerHTML += text.charAt(i);
                    i++;
                }
            }
            
            if (i < text.length) {
                setTimeout(type, speed);
            }
        }
        
        type();
    }
    
    // Add copy functionality to copy buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.action-button') && e.target.closest('.action-button').querySelector('i.fa-copy')) {
            const textToCopy = document.querySelector('.headline-text').textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show a small tooltip saying "Copied!"
                const copyButton = e.target.closest('.action-button');
                const originalHTML = copyButton.innerHTML;
                
                copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
                
                setTimeout(() => {
                    copyButton.innerHTML = originalHTML;
                }, 2000);
            });
        }
    });
    
    // Hide welcome container when starting a direct prompt 
    const promptInput = document.querySelector('.prompt-input') || document.querySelector('#prompt-input');
    if (promptInput) {
        promptInput.addEventListener('focus', function() {
            if (welcomeContainer) {
                welcomeContainer.style.display = 'none';
            }
        });
        
        // Hide on first keypress in the prompt
        promptInput.addEventListener('keyup', function(e) {
            if (e.target.value.trim() !== '' && welcomeContainer) {
                welcomeContainer.style.display = 'none';
            }
        });
    }
    
    // For submit button clicks (if you have a prompt form)
    const promptForm = document.querySelector('.prompt-form') || document.querySelector('#prompt-form');
    if (promptForm) {
        promptForm.addEventListener('submit', function() {
            if (welcomeContainer) {
                welcomeContainer.style.display = 'none';
            }
        });
    }
    
    // Listen for changes in window size or sidebar toggle
    window.addEventListener('resize', adjustLayout);
    
    // Check if there's a sidebar toggle button and add event listener
    const sidebarToggle = document.querySelector('.sidebar-toggle') || document.querySelector('[data-toggle="sidebar"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            // Small delay to let the sidebar animation complete
            setTimeout(adjustLayout, 300);
        });
    }
    
    function adjustLayout() {
        // Get the actual display width
        const displayWidth = window.innerWidth;
        
        // Get the chat container
        const chatContainer = document.querySelector('.chat-container');
        if (!chatContainer) return;
        
        // Adjust max-width based on sidebar state
        if (displayWidth < 768) {
            // Mobile view
            chatContainer.style.maxWidth = '100%';
        } else {
            // Desktop view with consistent width
            chatContainer.style.maxWidth = '950px';
        }
    }
    
    // Run layout adjustment on load
    adjustLayout();
});