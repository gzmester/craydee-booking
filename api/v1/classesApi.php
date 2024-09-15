<?php

class classesApi{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getClasses() {
        // Define the SQL query
        $sql = "SELECT c.class_id, c.class_name, c.instructor_id, c.description, c.max_participants, c.start_time, c.end_time, c.created_at,
                   COALESCE(COUNT(r.user_id), 0) AS participant_count
            FROM classes c
            LEFT JOIN class_registrations r
            ON c.class_id = r.class_id
            AND r.registration_date >= CURDATE()
            GROUP BY c.class_id";
        
        $result = $this->conn->query($sql);
    
        if ($result) {
            $classes = [];
            
            while ($row = $result->fetch_assoc()) {
                $classes[] = [
                    'class_id' => $row['class_id'],
                    'class_name' => $row['class_name'],
                    'instructor_id' => $row['instructor_id'],
                    'description' => $row['description'],
                    'max_participants' => $row['max_participants'],
                    'participant_count' => $row['participant_count'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'created_at' => $row['created_at']
                ];
            }
    
            return $classes;
        }
    
        return [];
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