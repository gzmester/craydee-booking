<?php 
// database class that simply handles the connection to the database and the queries, it makes it much easier to use within classes
// prepared satements are used to prevent sql injection
class Database {
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbName;
    private $conn;

    public function __construct() {
        require 'conf.php';
        $this->dbHost = $database['db_host'];
        $this->dbUser = $database['db_user'];
        $this->dbPass = $database['db_pass'];
        $this->dbName = $database['db_name'];

        $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql, ...$params) {
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Error preparing query: " . $this->conn->error);
            return null; // Return null on error
        }
    
        if (!empty($params)) {
            $types = '';
    
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
    
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
    
        if ($stmt->errno) {
            die("Error executing query: " . $stmt->error);
            return null; // Return null on error
        }
    
        if (stripos($sql, 'SELECT') === 0) {
            // This is a SELECT query, return the result set
            $result = $stmt->get_result();
    
            if ($result === false) {
                die("Error retrieving result: " . $stmt->error);
                return null; // Return null on error
            }
    
            return $result;
        } else {
            // For non-SELECT queries, return true on success
            return true;
        }
    }
    
    

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function close() {
        $this->conn->close();
    }
}
