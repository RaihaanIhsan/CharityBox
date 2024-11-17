<?php
require_once 'DatabaseConnection.php';
require_once 'UserRepository.php';
require_once 'MailService.php';

$db = new DatabaseConnection(); // Initialize database connection
$conn = $db->getConnection();   // Get connection object
$userRepo = new UserRepository($conn); // User repository for DB operations

$message = '';

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
    } elseif ($userRepo->isEmailRegistered($email)) {
        $message = "This email is already registered. Please use a different email.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(16));
        $user = [
            'full_name' => $full_name,
            'email' => $email,
            'password' => $hashed_password,
            'contact_number' => $contact_number,
            'address' => $address,
            'verification_token' => $verification_token
        ];

        if ($userRepo->createUser($user)) {
            $verification_link = "http://localhost:3000/charitybox/verify.php?token=$verification_token";
            if (MailService::sendVerificationEmail($email, $full_name, $verification_link)) {
                $message = "Registration successful. Please check your email to verify your account.";
            } else {
                $message = "Error sending verification email.";
            }
        } else {
            $message = "Error registering user.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CharityBox - Register</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="./styles.css/style.css">

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
            transform: translateX(100%); /* Start off-screen to the right */
            transition: visibility 0s, opacity 0.5s ease, transform 0.5s ease;
        }

        /* Pop-up visible state */
        .popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(0); /* Slide in from the right */
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
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="main-container">
    <form class="registration-form" action="register.php" method="POST">
        <h2>Sign Up</h2>
        
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="5" required>

        <label for="contact_number">Contact Number</label>
        <input type="tel" id="contact_number" name="contact_number" pattern="^\d{11}$" required>

        <label for="address">Address (Optional)</label>
        <textarea id="address" name="address" rows="3"></textarea>
        
        <div class="button-container">
        <input type="submit" name="register" value="Sign Up">
        </div>
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
