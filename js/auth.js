document.addEventListener('DOMContentLoaded', function() {
    function checkAuthStatus() {
        fetch('http://localhost/craydee-booking/api/v1/router.php/auth')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.loggedin) {
                        document.getElementById('loginLink').style.display = 'none';
                        document.getElementById('registerLink').style.display = 'none';
                        document.getElementById('logoutLink').style.display = 'block';
                        document.getElementById('usernameDisplay').textContent = `Welcome, ${data.username}`;
                    } else {
                        document.getElementById('loginLink').style.display = 'block';
                        document.getElementById('registerLink').style.display = 'block';
                        document.getElementById('logoutLink').style.display = 'none';
                    }
                } else {
                    console.error('Failed to check authentication status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    checkAuthStatus();
});
