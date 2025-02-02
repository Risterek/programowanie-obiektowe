<?php
class Database {
    private $conn;

    public function __construct($servername, $dbUsername, $dbPassword, $dbName) {
        $this->conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
        if ($this->conn->connect_error) {
            die("Brak połączenia: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}
?>