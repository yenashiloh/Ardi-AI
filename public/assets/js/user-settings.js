// Updated JavaScript to match your new field IDs
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const userSection = document.getElementById('open-user-modal');
    const userModal = document.getElementById('user-modal');
    const closeModalButtons = document.querySelectorAll('.close-modal, #close-modal');
    const modalOverlay = document.querySelector('.modal-overlay');
    const saveButton = document.querySelector('.save-button');
    
    // Function to open modal and load user data
    function openModal() {
        // Make the modal visible but with opacity 0
        userModal.style.display = 'block';
        
        // Force a reflow to ensure the display change is processed
        void userModal.offsetWidth;
        
        // Add active class
        userModal.classList.add('active');
        
        // Prevent scrolling when modal is open
        document.body.style.overflow = 'hidden';
        
        // Fetch current user settings from server (optional)
        fetchUserSettings();
    }
    
    // Function to close modal
    function closeModal() {
        userModal.classList.remove('active');
        
        // Use setTimeout to wait for any transitions to finish
        setTimeout(function() {
            userModal.style.display = 'none';
            document.body.style.overflow = '';
            
            // Reset form fields
            resetForm();
        }, 100);
    }
    
    // Function to fetch user settings
    function fetchUserSettings() {
        fetch('/user/settings', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Populate form fields with user data - FIXED: Updated field IDs
                document.getElementById('user-first-name').value = data.user.first_name;
                document.getElementById('user-last-name').value = data.user.last_name;
                document.getElementById('user-email-address').value = data.user.email;
            }
        })
        .catch(error => {
            console.error('Error fetching user settings:', error);
        });
    }
    
    // Function to reset form fields
    function resetForm() {
        const passwordFields = document.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            field.value = '';
        });
        
        // Remove any error styling
        const allInputs = document.querySelectorAll('.modal-input');
        allInputs.forEach(input => {
            input.style.borderColor = '';
            input.style.boxShadow = '';
            
            // Also remove error messages
            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.textContent = '';
            }
        });
    }
    
    // Event listeners for modal open/close
    if (userSection) {
        userSection.addEventListener('click', openModal);
    }
    
    closeModalButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
    
    // Close modal when clicking outside
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && userModal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Add password show/hide functionality
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        // Create container
        const container = document.createElement('div');
        container.className = 'password-container';
        
        // Insert container before input
        input.parentNode.insertBefore(container, input);
        
        // Move input into container
        container.appendChild(input);
        
        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'password-toggle';
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = '<i class="far fa-eye"></i>';
        container.appendChild(toggleBtn);
        
        // Add toggle functionality
        toggleBtn.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.innerHTML = '<i class="far fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                toggleBtn.innerHTML = '<i class="far fa-eye"></i>';
            }
        });
    });
    
    // Handle form submission
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            // Get form values - FIXED: Updated field IDs
            const firstName = document.getElementById('user-first-name').value;
            const lastName = document.getElementById('user-last-name').value;
            const currentPassword = document.getElementById('user-current-password').value;
            const newPassword = document.getElementById('user-password').value;
            const passwordConfirmation = document.getElementById('user-password-confirm').value;
            
            // Basic client-side validation
            let isValid = true;
            
            // FIXED: Updated field IDs for validation
            if (!firstName) {
                isValid = false;
                showError('user-first-name', 'First name is required');
            }
            
            if (!lastName) {
                isValid = false;
                showError('user-last-name', 'Last name is required');
            }
            
            // Password validation
            if (newPassword || passwordConfirmation) {
                if (newPassword !== passwordConfirmation) {
                    isValid = false;
                    showError('user-password-confirm', 'Passwords do not match');
                }
                
                if (!currentPassword) {
                    isValid = false;
                    showError('user-current-password', 'Current password is required');
                }
                
                if (newPassword && newPassword.length < 8) {
                    isValid = false;
                    showError('user-password', 'Password must be at least 8 characters');
                }
            }
            
            if (isValid) {
                // Show loading state
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                
                // Prepare data for submission
                const formData = {
                    first_name: firstName,
                    last_name: lastName
                };
                
                // Add password fields if provided
                if (newPassword) {
                    formData.current_password = currentPassword;
                    formData.new_password = newPassword;
                    formData.password_confirmation = passwordConfirmation;
                }
                
                // Send data to server
                fetch('/user/settings/update', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    saveButton.disabled = false;
                    saveButton.innerHTML = 'Save Changes';
                    
                    if (data.status === 'success') {
                        // Show success message
                        showNotification('Settings updated successfully', 'success');
                        
                        // Update displayed name in the UI
                        const userNameElement = document.querySelector('.user-name');
                        if (userNameElement) {
                            userNameElement.textContent = `${firstName} ${lastName}`;
                        }
                        
                        // Close modal
                        closeModal();
                    } else {
                        // Show error message
                        showNotification(data.message || 'Failed to update settings', 'error');
                        
                        // Show validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const fieldId = getFieldId(key);
                                if (fieldId) {
                                    showError(fieldId, data.errors[key][0]);
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating settings:', error);
                    saveButton.disabled = false;
                    saveButton.innerHTML = 'Save Changes';
                    showNotification('An error occurred. Please try again.', 'error');
                });
            }
        });
    }
    
    // Helper function to show error for a field
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.style.borderColor = 'red';
            field.style.boxShadow = '0 0 0 3px rgba(255, 0, 0, 0.2)';
            
            // Add error message
            let errorMsg = field.parentNode.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                field.parentNode.appendChild(errorMsg);
            }
            errorMsg.textContent = message;
            errorMsg.style.color = 'red';
            errorMsg.style.fontSize = '12px';
            errorMsg.style.marginTop = '5px';
            
            // Clear error on input
            field.addEventListener('input', function() {
                this.style.borderColor = '';
                this.style.boxShadow = '';
                if (errorMsg) {
                    errorMsg.textContent = '';
                }
            }, { once: true });
        }
    }
    
    // Helper function to map API field names to form field IDs
    function getFieldId(apiField) {
        // FIXED: Updated field mapping
        const fieldMap = {
            'first_name': 'user-first-name',
            'last_name': 'user-last-name',
            'current_password': 'user-current-password',
            'new_password': 'user-password',
            'password_confirmation': 'user-password-confirm'
        };
        
        return fieldMap[apiField];
    }
});

// Notification function - keep this outside the DOMContentLoaded event
function showNotification(message, type = 'success') {
    // Check if notification container exists, create if not
    let container = document.querySelector('.notification-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    // Set icon based on notification type
    let icon = 'fa-check-circle';
    if (type === 'error') icon = 'fa-exclamation-circle';
    if (type === 'info') icon = 'fa-info-circle';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    // Add notification to container
    container.appendChild(notification);
    
    // Add close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', function() {
        notification.style.animation = 'slideOutRight 0.3s forwards';
        setTimeout(() => {
            if (notification.parentNode) {
                container.removeChild(notification);
            }
        }, 300);
    });
    
    // Set initial animation
    notification.style.animation = 'slideInRight 0.3s forwards';
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    container.removeChild(notification);
                }
            }, 300);
        }
    }, 5000);
}

// Make sure we have the animations defined
if (!document.getElementById('notification-animations')) {
    const style = document.createElement('style');
    style.id = 'notification-animations';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}