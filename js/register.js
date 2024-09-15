document.getElementById('registrationForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    // Get the form data
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Check if passwords match does also check in the backend
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    // Create the data object to send
    const data = {
        username: username,
        email: email,
        password: password,
        confirm_password: confirmPassword
        };

    // Send a POST request to the registration API
    fetch('http://localhost/craydee-booking/api/v1/router.php/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json()) // Parse the JSON response
    .then(result => {
        if (result.success) {
            // Handle successful registration
            alert('Registration successful!');
            // Redirect or perform actions after registration
        } else {
            // Handle failed registration
            alert('Registration failed: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
