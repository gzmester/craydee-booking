<?php
// Database connection details
$host = 'localhost'; // or your database host
$user = 'root';      // or your database username
$pass = '';          // or your database password

// Connect to MySQL
$conn = new mysqli($host, $user, $pass);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$createDbQuery = "CREATE DATABASE IF NOT EXISTS sport_booking";
if ($conn->query($createDbQuery) === TRUE) {
    echo "Database 'sport_booking' created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db('sport_booking');

// Create the `users` table
$createUsersTable = "
    CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('member', 'admin') DEFAULT 'member',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
";
if ($conn->query($createUsersTable) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create the `facilities` table
$createFacilitiesTable = "
    CREATE TABLE IF NOT EXISTS facilities (
        facility_id INT AUTO_INCREMENT PRIMARY KEY,
        facility_name VARCHAR(255) NOT NULL,
        description TEXT,
        facility_type ENUM('court', 'hall', 'other') NOT NULL,
        hourly_rate DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
";
if ($conn->query($createFacilitiesTable) === TRUE) {
    echo "Table 'facilities' created successfully.<br>";
} else {
    die("Error creating facilities table: " . $conn->error);
}

// Create the `classes` table
$createClassesTable = "
    CREATE TABLE IF NOT EXISTS classes (
        class_id INT AUTO_INCREMENT PRIMARY KEY,
        class_name VARCHAR(255) NOT NULL,
        instructor_id INT NOT NULL,
        description TEXT,
        max_participants INT NOT NULL,
        start_time DATETIME NOT NULL,
        end_time DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (instructor_id) REFERENCES users(user_id)
    )
";
if ($conn->query($createClassesTable) === TRUE) {
    echo "Table 'classes' created successfully.<br>";
} else {
    die("Error creating classes table: " . $conn->error);
}

// Create the `bookings` table
$createBookingsTable = "
    CREATE TABLE IF NOT EXISTS bookings (
        booking_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        facility_id INT NOT NULL,
        booking_start DATETIME NOT NULL,
        booking_end DATETIME NOT NULL,
        total_cost DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (facility_id) REFERENCES facilities(facility_id),
        UNIQUE (facility_id, booking_start, booking_end)
    )
";
if ($conn->query($createBookingsTable) === TRUE) {
    echo "Table 'bookings' created successfully.<br>";
} else {
    die("Error creating bookings table: " . $conn->error);
}

// Create the `class_registrations` table
$createClassRegistrationsTable = "
    CREATE TABLE IF NOT EXISTS class_registrations (
        registration_id INT AUTO_INCREMENT PRIMARY KEY,
        class_id INT NOT NULL,
        user_id INT NOT NULL,
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (class_id) REFERENCES classes(class_id),
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        UNIQUE (class_id, user_id)
    )
";
if ($conn->query($createClassRegistrationsTable) === TRUE) {
    echo "Table 'class_registrations' created successfully.<br>";
} else {
    die("Error creating class registrations table: " . $conn->error);
}

// Close the connection
$conn->close();
