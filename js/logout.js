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
                // Redirect the user to the login page or any other desired page
                // Replace 'login.html' with the actual login page URL
                window.location.href = 'index.html';
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
