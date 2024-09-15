<?php

class bookingApi{


    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getBookings() {
        $sql = "SELECT * FROM bookings";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $bookings = $result->fetch_all(MYSQLI_ASSOC);
            return $bookings;
        }

        return [];
    }

    public function getBooking($id) {
        $sql = "SELECT * FROM bookings WHERE id = ?";
        $result = $this->conn->query($sql, $id);

        if ($result && $result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            return $booking;
        }

        return null;
    }

    public function getBookingsByUser($user_id) {
        $sql = "SELECT * FROM bookings WHERE user_id = ?";
        $result = $this->conn->query($sql, $user_id);

        if ($result && $result->num_rows > 0) {
            $bookings = $result->fetch_all(MYSQLI_ASSOC);
            return $bookings;
        }

        return [];
    }

    // booking start and booking end, is both datetime - as we are renting out facilities on a time basis, usually hourly basis.
    public function addBooking($user_id, $facility_id, $booking_start, $booking_end, $price) {
        $sql = "INSERT INTO bookings (user_id, facility_id, booking_start, booking_end, total_cost) VALUES (?, ?, ?, ?, ?)";
        $result = $this->conn->query($sql, $user_id, $facility_id, $booking_start, $booking_end, $price);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    

}