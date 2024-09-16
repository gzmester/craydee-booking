document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    // Get the username and password from the form
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Create the data object to send
    const data = {
        username: username,
        password: password
    };

    // Send a POST request to the login API
    fetch('http://localhost/craydee-booking/api/v1/router.php/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json()) // Parse the JSON response
    .then(result => {
        if (result.success) {
            // Handle successful login
            console.log(result.message);
            showSuccessAlert('Login successful!', 'You will be redirected to the homepage.');
            // wait for 2 seconds before redirecting
            setTimeout(() => {
                location.href = 'http://localhost/craydee-booking/index.html';
            }, 2000);
        } else {
            // Handle failed login
            alert('Login failed: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
