document.addEventListener('DOMContentLoaded', function() {
    // Fetch user data
    fetch('http://localhost/api/v1/router.php/user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: 1 // Assuming you have user_id available here
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data) {
            populateProfile(data);
        } else {
            console.error("User data not found or error in fetching.");
        }
    })
    .catch(error => {
        console.error('Error fetching user data:', error);
    });

    // Function to populate the profile page with user data
    function populateProfile(userData) {
        // Populate user details
        document.getElementById('username').textContent = userData.username;
        document.getElementById('email').textContent = userData.email;
        document.getElementById('role').textContent = userData.role;

        // Populate class registrations
        const classContainer = document.getElementById('class-registrations');
        classContainer.innerHTML = ''; // Clear previous entries
        userData.class_registrations.forEach(reg => {
            const classCard = `
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">${reg.class_name}</h5>
                        <p class="card-text">${reg.description}</p>
                        <p><strong>Start:</strong> ${new Date(reg.start_time).toLocaleString()}</p>
                        <p><strong>End:</strong> ${new Date(reg.end_time).toLocaleString()}</p>
                        <p><strong>Registered on:</strong> ${new Date(reg.registration_date).toLocaleString()}</p>
                    </div>
                </div>`;
            classContainer.innerHTML += classCard;
        });

        // Populate bookings
        const bookingsContainer = document.getElementById('bookings');
        bookingsContainer.innerHTML = ''; // Clear previous entries
        userData.bookings.forEach(booking => {
            const bookingCard = `
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">${booking.facility_name}</h5>
                        <p><strong>Start:</strong> ${new Date(booking.booking_start).toLocaleString()}</p>
                        <p><strong>End:</strong> ${new Date(booking.booking_end).toLocaleString()}</p>
                        <p><strong>Total Cost:</strong> $${booking.total_cost}</p>
                    </div>
                </div>`;
            bookingsContainer.innerHTML += bookingCard;
        });
    }
});
