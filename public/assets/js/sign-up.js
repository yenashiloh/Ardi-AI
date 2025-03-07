document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('passwordField');
    
    togglePassword.addEventListener('click', function() {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      if (type === 'password') {
        togglePassword.innerHTML = "<i class='bx bx-hide'></i>";
      } else {
        togglePassword.innerHTML = "<i class='bx bx-show'></i>";
      }
    });
    
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordField = document.getElementById('confirmPasswordField');
    
    toggleConfirmPassword.addEventListener('click', function() {
      const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPasswordField.setAttribute('type', type);
      
      if (type === 'password') {
        toggleConfirmPassword.innerHTML = "<i class='bx bx-hide'></i>";
      } else {
        toggleConfirmPassword.innerHTML = "<i class='bx bx-show'></i>";
      }
    });
  });