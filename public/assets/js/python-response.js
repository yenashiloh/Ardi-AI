document.addEventListener('DOMContentLoaded', function () {
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
                            <div class="headline-text">${text}</div>
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
            copyButton.addEventListener('click', function () {
                const textToCopy = messageDiv.querySelector('.headline-text').textContent;
                copyToClipboard(textToCopy, copyButton);
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
        navigator.clipboard.writeText(text).then(function () {
            // Store original button content
            const originalContent = button.innerHTML;

            // Change button text to 'Copied!'
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';

            // Restore original content after 2 seconds
            setTimeout(function () {
                button.innerHTML = originalContent;
            }, 2000);
        }, function () {
            // If copying fails
            console.error('Failed to copy text');
        });
    }

    // Function to handle sending message
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
            // Send query to backend
            const response = await fetch('/knowledge-base/query', {
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

            // Parse JSON response
            const data = await response.json();

            // Remove loading indicator
            loadingMessage.remove();

            if (response.ok && data.success) {
                // Add AI response
                addAIMessage(data.response);
            } else {
                // Show error message
                const errorMessage = data.error || 'Failed to get response from knowledge base';
                addAIMessage(`Error: ${errorMessage}`);
            }
        } catch (error) {
            // Remove loading indicator
            loadingMessage.remove();

            // Show error message
            addAIMessage('Sorry, there was an error connecting to the knowledge base. Please try again later.');
            console.error('Error:', error);
        }
    }

    // Event listeners
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }

    if (messageInput) {
        messageInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        });
    }
});
