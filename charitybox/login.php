<?php
session_start();
include 'config.php';  // Database connection

$message = '';  // Initialize the message variable

// Check if the verification parameter is set
if (isset($_GET['verification']) && $_GET['verification'] == 'success') {
    $message = "Your email has been verified successfully! You can now log in.";
}

if (isset($_GET['org_id'])) {
    $_SESSION['org_id'] = $_GET['org_id'];
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Check if the email is verified
        if ($user['is_verified'] == 0) {
            $message = "Please verify your email before logging in.";
        } else {
            if (password_verify($password, $user['password'])) {
                // Start the session and log the user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $message = "Login successful!";
                header("Location: donationform.php");
                exit();
            } else {
                $message = "Invalid password!";
            }
        }
    } else {
        $message = "No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Inline CSS for the popup and animation -->
    <style>
        /* Pop-up container */
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

        /* Pop-up visible state */
        .popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        /* Close button */
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

        .popup.error {
            background-color: #f44336; /* Red for error messages */
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="main-container">
    <form class="login-form" action="login.php" method="POST">
        <h2>Login</h2>
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <p style="margin-top: -2px;">
           <a href="forgotpassword.php">Forgot Password?</a>
        <div class="button-container">
            <input type="submit" name="login" value="Login">
        </div>
        <p style="text-align: center; margin-top: 15px;">
            Not registered yet? <a href="register.php">Sign Up</a>
        </p>
    </form>
</div>
<?php include 'footer.php'; ?>

<!-- Pop-up notification -->
<div id="popup" class="popup">
    <span id="popup-message"></span>
    <button class="close-btn" onclick="hidePopup()">✕</button>
</div>

<!-- JavaScript to handle the pop-up display -->
<script>
    // Show the pop-up if there is a PHP message
    window.onload = function() {
        var message = "<?php echo $message; ?>";
        if (message !== "") {
            var popup = document.getElementById('popup');
            document.getElementById('popup-message').textContent = message;

            // If the message is an error (invalid login), add error class
            if (message.includes('Invalid') || message.includes('No account') || message.includes('verify')) {
                popup.classList.add('error');
            }

            popup.classList.add('show');

            // Automatically hide the popup after 5 seconds
            setTimeout(hidePopup, 5000);
        }
    };

    // Function to hide the pop-up
    function hidePopup() {
        var popup = document.getElementById('popup');
        popup.classList.remove('show');
    }
</script>

</body>
</html>
