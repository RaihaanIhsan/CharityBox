<?php
// Include database connection file
include 'config.php';

// Initialize the message variable
$message = '';

if (isset($_GET['token'])) {
    $verification_token = $_GET['token'];

    // Check if the token exists in the database
    $sql = "SELECT * FROM users WHERE verification_token = '$verification_token' AND is_verified = 0";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Token exists, update user status to verified
        $sql_update = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = '$verification_token'";
        if (mysqli_query($conn, $sql_update)) {
            $message = "Your email has been verified successfully! You can now log in.";
        } else {
            $message = "Error updating record: " . mysqli_error($conn);
        }
    } else {
        $message = "This verification link is either invalid or has already been used.";
    }
} else {
    $message = "No verification token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="verification-message">
    <h2><?php echo $message; ?></h2>
    <a href="login.php">Go to Login</a>
</div>

</body>
</html>
