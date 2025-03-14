document.addEventListener('DOMContentLoaded', function () {
    // Step 1 to Step 2 transition
    document.getElementById('emailForm').addEventListener('submit', function (e) {
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
    document.getElementById('backButton').addEventListener('click', function () {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    });

    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordField = document.getElementById('passwordField');
    const confirmPasswordField = document.getElementById('confirmPasswordField');

    togglePassword.addEventListener('click', function (e) {
        e.preventDefault();
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
    });

    toggleConfirmPassword.addEventListener('click', function (e) {
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

    // Auto-capitalize first letter of first name and last name
    document.querySelector('input[name="first_name"]').addEventListener('input', function () {
        this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());
    });

    document.querySelector('input[name="last_name"]').addEventListener('input', function () {
        this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());
    });

    // Form submission
    document.getElementById('fullForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        // Get form data
        let firstName = document.querySelector('input[name="first_name"]').value.trim();
        let lastName = document.querySelector('input[name="last_name"]').value.trim();
        let email = document.getElementById('displayEmail').value.trim();
        let password = document.getElementById('passwordField').value;
        let confirmPassword = document.getElementById('confirmPasswordField').value;

        // Hide any existing messages
        document.querySelector('.success-message').style.display = 'none';
        document.querySelector('.error-message').style.display = 'none';

        // Validation for first_name and last_name (only letters and spaces)
        const lettersAndSpacesOnly = /^[A-Za-z\s]+$/;
        if (!lettersAndSpacesOnly.test(firstName)) {
            showMessageWithTimeout(document.querySelector('.error-message'), 'First name must contain only letters and spaces.');
            return;
        }
        if (!lettersAndSpacesOnly.test(lastName)) {
            showMessageWithTimeout(document.querySelector('.error-message'), 'Last name must contain only letters and spaces.');
            return;
        }

        // Validation for email
        if (!email || !email.includes('@')) {
            showMessageWithTimeout(document.querySelector('.error-message'), 'Please provide a valid email address.');
            return;
        }

        // Password validation
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        if (!passwordRegex.test(password)) {
            showMessageWithTimeout(document.querySelector('.error-message'),
                'Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.');
            return;
        }

        // Check if password contains first_name, last_name, or email parts
        const passwordLower = password.toLowerCase();
        const emailLocalPart = email.split('@')[0].toLowerCase();

        // Split first_name and last_name into words (in case of spaces)
        const firstNameWords = firstName.toLowerCase().split(/\s+/);
        const lastNameWords = lastName.toLowerCase().split(/\s+/);

        // Check each word in first_name, last_name, email
        for (let word of firstNameWords) {
            if (word.length > 2 && passwordLower.includes(word)) {
                showMessageWithTimeout(document.querySelector('.error-message'),
                    'Password cannot contain your first name.');
                return;
            }
        }

        for (let word of lastNameWords) {
            if (word.length > 2 && passwordLower.includes(word)) {
                showMessageWithTimeout(document.querySelector('.error-message'),
                    'Password cannot contain your last name.');
                return;
            }
        }

        // Check if password contains the entire local part
        if (passwordLower.includes(emailLocalPart)) {
            showMessageWithTimeout(document.querySelector('.error-message'),
                'Password cannot contain your email address.');
            return;
        }

        // Split email local part into segments based on common separators
        const emailSegments = emailLocalPart.split(/[_.-]/);

        // Check each segment of the email
        for (let segment of emailSegments) {
            if (segment.length > 2 && passwordLower.includes(segment)) {
                showMessageWithTimeout(document.querySelector('.error-message'),
                    'Password cannot contain parts of your email address.');
                return;
            }
        }

        // Email validation - find common substrings that might be in the password
        function findCommonSubstrings(email, password) {
            const emailLower = email.toLowerCase();
            const passwordLower = password.toLowerCase();

            // Check for substrings of at least 4 characters
            for (let i = 0; i < emailLower.length - 3; i++) {
                for (let j = i + 4; j <= Math.min(i + 20, emailLower.length); j++) {
                    const substring = emailLower.substring(i, j);
                    if (passwordLower.includes(substring)) {
                        return substring;
                    }
                }
            }
            return null;
        }

        // Add this to your form submission code, after the existing email validation
        // but before the password match check
        const commonSubstring = findCommonSubstrings(emailLocalPart, passwordLower);
        if (commonSubstring) {
            showMessageWithTimeout(
                document.querySelector('.error-message'),
                `Password cannot contain parts of your email address.`
            );
            return;
        }

        // Check if passwords match
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
                window.location.href = `/emails/verify-email?user_id=${result.user_id}`;
            } else {
                try {
                    let errorResult = await response.json();
                    if (errorResult.errors && errorResult.errors.email) {
                        if (errorResult.errors.email.includes('already') ||
                            errorResult.errors.email.includes('taken') ||
                            errorResult.errors.email.includes('unique')) {
                            showMessageWithTimeout(document.querySelector('.error-message'),
                                'This email is already registered. Please use a different email.');
                        } else {
                            showMessageWithTimeout(document.querySelector('.error-message'), errorResult.errors.email[0]);
                        }
                    } else {
                        showMessageWithTimeout(document.querySelector('.error-message'),
                            errorResult.message || 'Registration failed. Please try again.');
                    }
                } catch (e) {
                    showMessageWithTimeout(document.querySelector('.error-message'),
                        'Registration failed. Please try again.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showMessageWithTimeout(document.querySelector('.error-message'), 'Registration failed. Please try again.');
        }
    });
});
