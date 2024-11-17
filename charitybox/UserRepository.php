<?php

class UserRepository {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function isEmailRegistered($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function createUser($user) {
        $sql = "INSERT INTO users (full_name, email, password, contact_number, address, verification_token, is_verified)
                VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssss",
            $user['full_name'],
            $user['email'],
            $user['password'],
            $user['contact_number'],
            $user['address'],
            $user['verification_token']
        );
        return $stmt->execute();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
