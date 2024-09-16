document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost/craydee-booking/api/v1/router.php/classes')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('classes-list');
            container.innerHTML = data.map(classItem => {
                return `
                    <div class="col-md-6 col-lg-3">
                        <div class="card class-card shadow p-3 mb-5 bg-body rounded">
                            <img src="https://aanmc.org/wp-content/uploads/2018/12/iStock-8743768402.jpg?text=${encodeURIComponent(classItem.class_name)}" class="card-img-top" alt="${classItem.class_name}">
                            <div class="card-body">
                                <h5 class="card-title">${classItem.class_name}</h5>
                                <p class="card-text">${classItem.description || 'No description available.'}</p>
                                <hr>
                                <p class="card-text"><strong>Instructor:</strong> ${classItem.instructor_id}</p>
                                <p class="card-text"><strong>Max Participants:</strong> ${classItem.max_participants}</p>
                                <p class="card-text"><strong>Start Time:</strong> ${new Date(classItem.start_time).toLocaleString()}</p>
                                <p class="card-text"><strong>End Time:</strong> ${new Date(classItem.end_time).toLocaleString()}</p>
                                <button class="btn btn-primary float-end join-class" data-class-id="${classItem.class_id}">Join</button>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Created at: ${new Date(classItem.created_at).toLocaleString()}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Event listener for joining class
            document.querySelectorAll('.join-class').forEach(button => {
                button.addEventListener('click', function() {
                    const classId = this.getAttribute('data-class-id');
                    // Handle class join logic here
                    alert(`Joining class with ID: ${classId}`);
                });
            });
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
        });
});
