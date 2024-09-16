document.getElementById('logoutBtn').addEventListener('click', function (event) {

    // Make a request to the API to log out the user
    // Replace 'apiEndpoint' with the actual endpoint of your API
    fetch('http://localhost/craydee-booking/api/v1/router.php/logout', {
        method: 'GET',
    })
        .then(response => {
            // Handle the response from the API
            if (response.ok) {
                // User successfully logged out
                console.log('User logged out');
                showSuccessAlert('Logged out', 'You will be redirected to the login page.');
                // Wait for 2 seconds before redirecting
                setTimeout(() => {
                    location.href = 'login.html';
                }, 2000);
            } else {
                // Failed to log out the user
                console.error('Failed to log out the user');
            }
        })
        .catch(error => {
            // Handle any errors that occurred during the request
            console.error('An error occurred:', error);
        });
});
