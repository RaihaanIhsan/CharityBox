<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

class MailService {
    public static function sendVerificationEmail($recipientEmail, $recipientName, $verificationLink) {
        $phpmailer = new PHPMailer(true);
        try {
            $phpmailer->isSMTP();
            $phpmailer->Host = 'smtp.gmail.com';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = 'charitybox60@gmail.com'; // Your Gmail address
            $phpmailer->Password = 'oxqs knuv dpow pumc';     // Your App Password
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->Port = 587;

            $phpmailer->setFrom('charitybox60@gmail.com', 'CharityBox');
            $phpmailer->addAddress($recipientEmail, $recipientName);

            $phpmailer->isHTML(true);
            $phpmailer->Subject = 'Verify Your Email Address';
            $phpmailer->Body = "Hi $recipientName,<br><br>Please verify your email address by clicking the link below:<br><a href='$verificationLink'>$verificationLink</a>";

            $phpmailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
