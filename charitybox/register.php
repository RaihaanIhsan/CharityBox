<?php
// Include database connection file
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once '../vendor/autoload.php';

// Initialize the message variable
$message = '';

if (isset($_POST['register'])) {
    // Capture form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
    } else {
        // Check if email already exists
        $sql_check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql_check_email);
        
        if (mysqli_num_rows($result) > 0) {
            $message = "This email is already registered. Please use a different email.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate a unique verification token
            $verification_token = bin2hex(random_bytes(16));
            
            // Insert user into the database with verification token and unverified status
            $sql_insert = "INSERT INTO users (full_name, email, password, contact_number, address, verification_token, is_verified) 
                            VALUES ('$full_name', '$email', '$hashed_password', '$contact_number', '$address', '$verification_token', 0)";
            
            if (mysqli_query($conn, $sql_insert)) {
                // Send the verification email using PHPMailer
$phpmailer = new PHPMailer(true); // Create a new PHPMailer instance
try {
    // Server settings
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = 'charitybox60@gmail.com'; // Your Gmail address
    $phpmailer->Password = 'oxqs knuv dpow pumc'; // Your App Password
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Port = 587;

    // Recipients
    $phpmailer->setFrom('charitybox60@gmail.com', 'CharityBox'); // Your sender email
    $phpmailer->addAddress($email, $full_name); // Add the recipient

    // Content
    $phpmailer->isHTML(true); // Set email format to HTML
    $phpmailer->Subject = 'Verify Your Email Address';
    $verification_link = "http://localhost:3000/charitybox/verify.php?token=$verification_token"; // Adjust for local testing
    $phpmailer->Body = "Hi $full_name,<br><br>Please verify your email address by clicking on the link below:<br><a href='$verification_link'>$verification_link</a><br><br>Thank you!";

    // Send the email
    $phpmailer->send();
    $message = "Registration successful. Please check your email to verify your account.";
} catch (Exception $e) {
    $message = "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
}
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
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
