<?php

class facilityApi {


    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getFacilities() {
        $sql = "SELECT * FROM facilities";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $facilities = $result->fetch_all(MYSQLI_ASSOC);
            return $facilities;
        }

        return [];
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