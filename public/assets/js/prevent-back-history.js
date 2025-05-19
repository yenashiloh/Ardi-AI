 window.addEventListener('pageshow', function(event) {
        // If the page was loaded from the browser cache (back/forward button)
        if (event.persisted) {
            // Force page reload to check authentication status
            window.location.reload();
        }
    });
    
    // Additional protection: check authentication status periodically
    // This helps if the session expires while the page is open
    function checkAuthStatus() {
        fetch('/check-auth-status', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated && document.querySelector('.user-section')) {
                // If user is not authenticated but user section is visible, reload the page
                window.location.reload();
            }
        })
        .catch(error => console.error('Error checking auth status:', error));
    }
    
    // Check authentication status every 5 minutes
    if (document.querySelector('.user-section')) {
        setInterval(checkAuthStatus, 300000); // 5 minutes in milliseconds
    }