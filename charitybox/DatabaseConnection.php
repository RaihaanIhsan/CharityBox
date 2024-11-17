<?php

class DatabaseConnection {
    private $connection;

    public function __construct($host = "localhost", $user = "root", $password = "", $database = "charitybox_db") {
        $this->connection = new mysqli($host, $user, $password, $database);

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
