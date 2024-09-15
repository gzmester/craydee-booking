<?php

class classesApi{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getClasses() {
        $sql = "SELECT * FROM classes";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $classes = $result->fetch_all(MYSQLI_ASSOC);
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