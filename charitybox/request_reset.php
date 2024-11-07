<?php
session_start();
include 'config.php';  // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$message = '';  // Initialize the message variable

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // User exists, generate token and expiration time
        $token = bin2hex(random_bytes(32));  // Generate a 64-character token
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expires in 1 hour
        
        // Save token and expiration in the database
        $sql = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        if (mysqli_query($conn, $sql)) {
            // Prepare reset link
            $resetLink = "http://localhost:3000/charitybox/reset_password.php?token=" . $token;

            // Send the reset email with PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'charitybox60@gmail.com';
                $mail->Password = 'oxqs knuv dpow pumc';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipient and sender settings
                $mail->setFrom('charitybox60@gmail.com', 'CharityBox');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "We received a password reset request for your account. Click the link below to reset your password:<br><br>
                               <a href='$resetLink'>$resetLink</a><br><br>
                               If you did not request this, please ignore this email.";

                $mail->send();
                $message = 'Password reset email has been sent successfully.';
            } catch (Exception $e) {
                $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Error saving reset token: " . $conn->error;
        }
    } else {
        $message = "No account found with that email!";
    }

    // Store the message in session and redirect to the form page
    $_SESSION['message'] = $message;
    header('Location: forgotpassword.php'); // Redirect to the form page
    exit();
}
?>
