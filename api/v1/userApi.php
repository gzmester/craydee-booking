<?php

class userApi{



    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($username, $password) // to include csrf token to prevent csrf attacks
    {
       
    }

    public function register($username, $email, $password) // to include csrf token
    {
        
        

    }

    public function logout()
    {
        session_destroy();
        http_response_code(200);
        echo "Logged out";
    }
}