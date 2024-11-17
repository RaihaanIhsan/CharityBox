<?php

class UserService {
    private $userRepo;

    public function __construct($userRepo) {
        $this->userRepo = $userRepo;
    }

    public function authenticateUser($email, $password) {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => "No account found with that email!"];
        }

        if ($user['is_verified'] == 0) {
            return ['success' => false, 'message' => "Please verify your email before logging in."];
        }

        if (password_verify($password, $user['password'])) {
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => "Invalid password!"];
        }
    }
}
