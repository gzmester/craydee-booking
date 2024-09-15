<?php

class userApi{



    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($username, $password) // to include csrf token to prevent csrf attacks
    {

        $sql = "SELECT id, username, password_hash FROM users WHERE username = ?";
        $result = $this->conn->query($sql, $username);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                return true;
            }
        }

        return false;
       
    }

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
    
    public function logout()
    {
        session_destroy();
        http_response_code(200);
        echo "Logged out";
    }
}
