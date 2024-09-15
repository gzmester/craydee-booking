<?php

class facilityApi {


    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getFacilities() {
        // Define the SQL query
        $sql = "SELECT f.facility_id, f.facility_name, f.description, f.facility_type, f.hourly_rate, f.created_at, 
                       b.booking_start, b.booking_end 
                FROM facilities f 
                LEFT JOIN bookings b 
                ON f.facility_id = b.facility_id 
                AND b.booking_start >= CURDATE()";  // Only consider bookings from today onwards
    
        $result = $this->conn->query($sql);
    
        // array to store facilities
        $facilities = [];
    
        // Populate facilities even if no bookings exist
        while ($row = $result->fetch_assoc()) {
            $facility_id = $row['facility_id'];
    
            // If this facility has not been added yet, add it
            if (!isset($facilities[$facility_id])) {
                $facilities[$facility_id] = [
                    'facility_id' => $row['facility_id'],
                    'facility_name' => $row['facility_name'],
                    'description' => $row['description'],
                    'facility_type' => $row['facility_type'],
                    'hourly_rate' => $row['hourly_rate'],
                    'created_at' => $row['created_at'],
                    'bookings' => []  // Initialize empty bookings array
                ];
            }
    
            // Add booking information if it exists
            if (!empty($row['booking_start']) && !empty($row['booking_end'])) {
                $facilities[$facility_id]['bookings'][] = [
                    'booking_start' => $row['booking_start'],
                    'booking_end' => $row['booking_end']
                ];
            }
        }
    
        // Return facilities, even if they have no bookings
        return array_values($facilities);
    }
    
    public function getFacility($id) {
        $sql = "SELECT * FROM facilities WHERE id = ?";
        $result = $this->conn->query($sql, $id);

        if ($result && $result->num_rows > 0) {
            $facility = $result->fetch_assoc();
            return $facility;
        }

        return null;
    }

    public function addFacility($name, $description, $price) {
        $sql = "INSERT INTO facilities (name, description, price) VALUES (?, ?, ?)";
        $result = $this->conn->query($sql, $name, $description, $price);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateFacility($id, $name, $description, $price) {
        $sql = "UPDATE facilities SET name = ?, description = ?, price = ? WHERE id = ?";
        $result = $this->conn->query($sql, $name, $description, $price, $id);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFacility($id) {
        $sql = "DELETE FROM facilities WHERE id = ?";
        $result = $this->conn->query($sql, $id);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }


}