document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('passwordField');
    
    togglePassword.addEventListener('click', function() {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      // Toggle the eye icon
      if (type === 'password') {
        togglePassword.innerHTML = "<i class='bx bx-hide'></i>";
      } else {
        togglePassword.innerHTML = "<i class='bx bx-show'></i>";
      }
    });
  });