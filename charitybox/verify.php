<?php
// Include database connection file
include 'config.php';

if (isset($_GET['token'])) {
    // Get the verification token from the URL
    $token = $_GET['token'];

    // Prepare SQL query to find user by verification token
    $sql = "SELECT * FROM users WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid; update the user's status to verified
        $user = $result->fetch_assoc();
        $update_sql = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user['id']);
        if ($update_stmt->execute()) {
            echo "Email verified successfully! You can now <a href='login.php'>log in</a>.";
        } else {
            echo "Error verifying email: " . $conn->error;
        }
    } else {
        // Token is invalid
        echo "Invalid verification token.";
    }
} else {
    echo "No token provided.";
}
?>
