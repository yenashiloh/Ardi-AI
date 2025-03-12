document.addEventListener('DOMContentLoaded', function() {
    // Step 1 to Step 2 transition
    document.getElementById('emailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get email value
        const email = document.getElementById('emailInput').value;
        
        // Display email in step 2 and ensure it has a value for form submission
        document.getElementById('displayEmail').value = email;
        
        // Hide step 1, show step 2
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    });
    
    // Back button functionality
    document.getElementById('backButton').addEventListener('click', function() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    });
    
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordField = document.getElementById('passwordField');
    const confirmPasswordField = document.getElementById('confirmPasswordField');
    
    togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
    });
    
    toggleConfirmPassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
    });
    
    // Function to show message with timeout
    function showMessageWithTimeout(messageElement, text, duration = 5000) {
        messageElement.textContent = text;
        messageElement.style.display = 'block';
        
        // Clear any existing timeout
        if (messageElement.timeoutId) {
            clearTimeout(messageElement.timeoutId);
        }
        
        // Set new timeout
        messageElement.timeoutId = setTimeout(() => {
            messageElement.style.display = 'none';
        }, duration);
    }
    
    // Form submission
    document.getElementById('fullForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data
        let firstName = document.querySelector('input[name="first_name"]').value;
        let lastName = document.querySelector('input[name="last_name"]').value;
        let email = document.getElementById('displayEmail').value; // Get the email from the display field
        let password = document.getElementById('passwordField').value;
        let confirmPassword = document.getElementById('confirmPasswordField').value;

        // Hide any existing messages
        document.querySelector('.success-message').style.display = 'none';
        document.querySelector('.error-message').style.display = 'none';

        // Validation
        if (!email || !email.includes('@')) {
            showMessageWithTimeout(document.querySelector('.error-message'), 'Please provide a valid email address');
            return;
        }

        if (password !== confirmPassword) {
            showMessageWithTimeout(document.querySelector('.error-message'), 'Passwords do not match!');
            return;
        }
        
        // Get CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Create form data
        const formData = {
            first_name: firstName,
            last_name: lastName,
            email: email,
            password: password,
            password_confirmation: confirmPassword
        };

        console.log("Submitting form data:", formData); // Debug log

        try {
            let response = await fetch('/register', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                let result = await response.json();
                
                // Redirect immediately to verification page without showing success message
                window.location.href = `/emails/verify-email?user_id=${result.user_id}`;
            } else {
                // Try to parse JSON response
                try {
                    let errorResult = await response.json();
                    
                    // Specific handling for email already exists error
                    if (errorResult.errors && errorResult.errors.email) {
                        // Check if the error is about email already existing
                        if (errorResult.errors.email.includes('already') || 
                            errorResult.errors.email.includes('taken') || 
                            errorResult.errors.email.includes('unique')) {
                            showMessageWithTimeout(document.querySelector('.error-message'), 'This email is already registered. Please use a different email.');
                        } else {
                            showMessageWithTimeout(document.querySelector('.error-message'), errorResult.errors.email[0]);
                        }
                    } else {
                        // General error message
                        showMessageWithTimeout(document.querySelector('.error-message'), 
                            errorResult.message || 'Registration failed. Please try again.');
                    }
                } catch (e) {
                    // If not JSON, show generic message
                    showMessageWithTimeout(document.querySelector('.error-message'), 'Registration failed. Please try again.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showMessageWithTimeout(document.querySelector('.error-message'), 'Registration failed. Please try again.');
        }
    });
});