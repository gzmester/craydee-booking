document.addEventListener('DOMContentLoaded', function() {
    // Get current date and time
    const now = new Date();
    
    // Calculate the offset (CET/CEST) in hours
    const timezoneOffset = +1; // CET is UTC+1, adjust if in CEST (UTC+2)

    // Create a new Date object with the offset applied
    const localDate = new Date(now.getTime() + (timezoneOffset * 60 * 60 * 1000));
    const today = localDate.toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
    console.log('Today:', today);
    console.log('Local date:', localDate);
    const container = document.getElementById('facilities-list');

    fetch('http://localhost/craydee-booking/api/v1/router.php/facilities')
        .then(response => response.json())
        .then(data => {
            container.innerHTML = data.map(facility => {
                const availableTimes = Array.from({ length: 11 }, (_, i) => `${8 + i}:00`);

                const timesButtons = availableTimes.map((time, index) => {
                    const timeSlotStart = `${8 + index}:00`;
                    const timeSlotEnd = `${8 + index + 1}:00`;

                    const isBooked = facility.bookings.some(booking => {
                        const bookingStart = new Date(booking.booking_start).getHours();
                        const bookingEnd = new Date(booking.booking_end).getHours();
                        const startHour = parseInt(timeSlotStart.split(':')[0], 10);

                        return startHour >= bookingStart && startHour < bookingEnd;
                    });

                    return `
                        <button class="btn btn-${isBooked ? 'secondary' : 'outline-primary'} btn-sm" 
                                data-facility-id="${facility.facility_id}" 
                                data-time="${timeSlotStart}" 
                                data-end-time="${timeSlotEnd}" 
                                ${isBooked ? 'disabled' : ''}>
                            ${timeSlotStart}
                        </button>
                    `;
                }).join('');

                return `
                    <div class="facility-card mb-4">
                        <img src="https://via.placeholder.com/600x200.png?text=${encodeURIComponent(facility.facility_name)}" alt="${facility.facility_name}">
                        <div class="facility-card-body">
                            <h5 class="card-title">${facility.facility_name}</h5>
                            <p class="card-text">${facility.description || 'No description available.'}</p>
                            <p class="card-text"><strong>Type:</strong> ${facility.facility_type}</p>
                            <p class="card-text"><strong>Hourly Rate:</strong> $${facility.hourly_rate}</p>
                            <p class="card-text"><small class="text-muted">Created at: ${new Date(facility.created_at).toLocaleString()}</small></p>
                        </div>
                        <div class="facility-card-footer">
                            <div class="availability-buttons">
                                ${timesButtons}
                            </div>
                        </div>
                        <button class="btn btn-success mt-2 btn-book" data-facility-id="${facility.facility_id}" disabled>Book</button>
                    </div>
                `;
            }).join('');

            setupBooking();
        })
        .catch(error => {
            console.error('Error fetching facilities:', error);
        });

    function setupBooking() {
        // Handle time button clicks
        document.querySelectorAll('.availability-buttons button').forEach(button => {
            button.addEventListener('click', function() {
                const facilityId = this.getAttribute('data-facility-id');
                const startTime = this.getAttribute('data-time');
                const endTime = this.getAttribute('data-end-time');

                // Append the current date to the time
                const fullStartTime = `${today} ${startTime}:00`;
                const fullEndTime = `${today} ${endTime}:00`;

                const facilityCard = this.closest('.facility-card');
                const price = facilityCard.getAttribute('data-price');

                const bookButton = document.querySelector(`.btn-book[data-facility-id="${facilityId}"]`);
                bookButton.removeAttribute('disabled');
                bookButton.dataset.startTime = fullStartTime;
                bookButton.dataset.endTime = fullEndTime;
                bookButton.dataset.price = price;

                // Highlight selected button (optional)
                this.classList.add('btn-selected');
            });
        });

        // Handle booking
        document.querySelectorAll('.btn-book').forEach(bookButton => {
            bookButton.addEventListener('click', function() {
                const facilityId = this.getAttribute('data-facility-id');
                const startTime = this.getAttribute('data-start-time');
                const endTime = this.getAttribute('data-end-time');
                const price = this.getAttribute('data-price');

                if (facilityId && startTime && endTime && price) {
                    const bookingData = {
                        facility_id: facilityId,
                        start_time: startTime,
                        end_time: endTime,
                        price: price
                    };

                    fetch('http://localhost/craydee-booking/api/v1/router.php/book', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(bookingData)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Booking successful! ' + result.message);
                            location.reload();
                        } else {
                            alert('Booking failed: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error making booking:', error);
                    });
                }
            });
        });
    }
});
