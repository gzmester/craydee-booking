<?php

class classesApi{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getClasses() {
        $sql = "SELECT c.*, COUNT(r.user_id) as registration_count
            FROM classes c
            LEFT JOIN class_registrations r
            ON c.class_id = r.class_id
            AND r.registration_date >= CURDATE()
            GROUP BY c.class_id";
    
        $result = $this->conn->query($sql);
    
        if ($result) {
            $classes = [];
            while ($row = $result->fetch_assoc()) {
                $classes[] = $row;
            }
    
            // Return classes with registration counts
            return $classes;
        } else {
            error_log("Error retrieving classes: " . $this->conn->error);
            return [];
        }
    }
    
    

    public function getClass($id) {
        $sql = "SELECT * FROM classes WHERE id = ?";
        $result = $this->conn->query($sql, $id);

        if ($result && $result->num_rows > 0) {
            $class = $result->fetch_assoc();
            return $class;
        }

        return null;
    }

    public function addClass($name, $description, $price) {
        $sql = "INSERT INTO classes (name, description, price) VALUES (?, ?, ?)";
        $result = $this->conn->query($sql, $name, $description, $price);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateClass($id, $name, $description, $price) {
        $sql = "UPDATE classes SET name = ?, description = ?, price = ? WHERE id = ?";
        $result = $this->conn->query($sql, $name, $description, $price, $id);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteClass($id) {
        $sql = "DELETE FROM classes WHERE id = ?";
        $result = $this->conn->query($sql, $id);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}