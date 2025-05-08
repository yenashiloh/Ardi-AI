document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('passwordForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordHelp = document.getElementById('passwordHelp');
    const confirmPasswordHelp = document.getElementById('confirmPasswordHelp');

    function validatePasswordStrength(password) {
        const minLength = 6;
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const hasNumber = /\d/.test(password);
        return password.length >= minLength && hasSpecial && hasNumber;
    }

    function showValidationMessages() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        let valid = true;

        // Password strength
        if (password === '') {
            passwordHelp.textContent = '';
        } else if (!validatePasswordStrength(password)) {
            passwordHelp.textContent =
                'Password must be at least 6 characters, include a number and a special character.';
            valid = false;
        } else {
            passwordHelp.textContent = '';
        }

        // Password match
        if (confirmPassword === '') {
            confirmPasswordHelp.textContent = '';
        } else if (password !== confirmPassword) {
            confirmPasswordHelp.textContent = 'Passwords do not match.';
            valid = false;
        } else {
            confirmPasswordHelp.textContent = '';
        }

        return valid;
    }

    passwordInput.addEventListener('input', showValidationMessages);
    confirmPasswordInput.addEventListener('input', showValidationMessages);

    form.addEventListener('submit', function (e) {
        if (!showValidationMessages()) {
            e.preventDefault();
        }
    });

    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
});
