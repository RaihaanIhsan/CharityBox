<?php
session_start(); // Start session for message handling
include 'config.php'; // Database connection
$token = $_GET['token'] ?? '';
$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];

    // Check if token is non-empty before querying
    if (!empty($token)) {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token' AND reset_expires > NOW()");
        if (mysqli_num_rows($result) > 0) {
            // Process password reset
            $user = mysqli_fetch_assoc($result);
            $sql = "UPDATE users SET password='$newPassword', reset_token=NULL, reset_expires=NULL WHERE reset_token='$token'";
            if (mysqli_query($conn, $sql)) {
                $message = "Password has been reset successfully!";
            } else {
                $message = "Error updating password. Please try again.";
            }
        } else {
            $message = "Invalid or expired token!";
        }
    } else {
        $message = "Token missing. Please ensure you used the correct link from your email.";
    }
}

// Store message in session for pop-up display
if (!empty($message)) {
    $_SESSION['message'] = $message;
    header('Location: reset_password.php?token=' . $token); // Redirect to the same page with token
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Include the pop-up styling here */
        .popup {
            position: fixed;
            top: 30px;
            right: 30px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            transition: visibility 0s, opacity 0.5s ease, transform 0.5s ease;
        }
        .popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        .popup.error {
            background-color: #f44336; /* Red for error messages */
        }
        .popup .close-btn {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-container">
    <div class="login-form">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" required>
            <label for="password">New Password</label>
            <input type="password" name="password" required>
            <div class="button-container">
            <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Pop-up notification -->
<div id="popup" class="popup">
    <span id="popup-message"></span>
    <button class="close-btn" onclick="hidePopup()">✕</button>
</div>

<script>
    window.onload = function() {
        var message = "<?php echo isset($_SESSION['message']) ? addslashes($_SESSION['message']) : ''; ?>"; // Safely output the message
        if (message !== "") {
            var popup = document.getElementById('popup');
            document.getElementById('popup-message').textContent = message;

            // If the message indicates an error, add error class
            if (message.includes('Error') || message.includes('Invalid') || message.includes('Token missing')) {
                popup.classList.add('error');
            }

            popup.classList.add('show');
            setTimeout(hidePopup, 5000); // Automatically hide the popup after 5 seconds
        }
    };

    function hidePopup() {
        var popup = document.getElementById('popup');
        popup.classList.remove('show');
    }
</script>
</body>
</html>
