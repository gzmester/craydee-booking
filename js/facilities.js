document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost/craydee-booking/api/v1/router.php/facilities')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('facilities-list');
            container.innerHTML = data.map(facility => {
                // Dummy times for demonstration; replace with real times if available
                const availableTimes = Array.from({length: 11}, (_, i) => `${8 + i}:00`);
                
                const timesButtons = availableTimes.map(time => `
                    <button class="btn btn-outline-primary btn-sm">${time}</button>
                `).join('');

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
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error fetching facilities:', error);
        });
});
