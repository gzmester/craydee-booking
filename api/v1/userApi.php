<?php

class userApi{



    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($username, $password) // to include csrf token to prevent csrf attacks
    {

        $sql = "SELECT id, username, password_hash, role FROM users WHERE username = ?";
        $result = $this->conn->query($sql, $username);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }

        return false;
       
    }

    // need to do some proper return messages here
    public function register($username, $email, $password) {
        // Check if username or email already exists
        if ($this->userExists($username, $email)) {
            return array('success' => false, 'message' => 'Username or email already exists.');
        }
    
        // Validate password strength (at least 8 characters)
        if (strlen($password) < 8) {
            return array('success' => false, 'message' => 'Password must be at least 8 characters long.');
        }
    
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $result = $this->conn->query($sql, $username, $email, $passwordHash);
    
        if ($result) {
           return true;
        } else {
            return false;
        }
    }
    

    private function userExists($username, $email) {
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $result = $this->conn->query($sql, $username, $email);
        
        if ($result && $result->num_rows > 0) {
            return true; // User exists
        }
        
        return false; // User does not exist
    }

    public function getUserById($id) {
        $sql = "SELECT 
        u.id, u.username, u.email, u.role,
        
        -- Booking Details
        b.booking_id, b.booking_start, b.booking_end, b.total_cost,
        f.facility_id, f.facility_name,

        -- Class Registration Details
        cr.registration_id, cr.registration_date,
        c.class_id, c.class_name, c.description, c.start_time, c.end_time, c.max_participants
        
    FROM users u
    
    -- Join with bookings and facilities
    LEFT JOIN bookings b ON u.id = b.user_id
    LEFT JOIN facilities f ON b.facility_id = f.facility_id
    
    -- Join with class registrations and classes
    LEFT JOIN class_registrations cr ON u.id = cr.user_id
    LEFT JOIN classes c ON cr.class_id = c.class_id
    
    WHERE u.id = ?";
            
        $result = $this->conn->query($sql, $id);
       
    
        if ($result && $result->num_rows > 0) {
            $userData = [];
            
            // Fetch all related data
            while ($row = $result->fetch_assoc()) {
                $userData['id'] = $row['id'];
                $userData['username'] = $row['username'];
                $userData['email'] = $row['email'];
                $userData['role'] = $row['role'];
                
                // Collect class registrations
                $userData['class_registrations'][] = [
                    'registration_id' => $row['registration_id'],
                    'class_name' => $row['class_name'],
                    'description' => $row['description'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'max_participants' => $row['max_participants'],
                    'registration_date' => $row['registration_date']
                ];
                
                // Collect bookings
                $userData['bookings'][] = [
                    'booking_id' => $row['booking_id'],
                    'facility_name' => $row['facility_name'],
                    'booking_start' => $row['booking_start'],
                    'booking_end' => $row['booking_end'],
                    'total_cost' => $row['total_cost']
                ];
            }
            return $userData;
        } else {
            return null;
        }
    }
    
    
    public function logout()
    {
        session_destroy();
        http_response_code(200);
        
    }
}
