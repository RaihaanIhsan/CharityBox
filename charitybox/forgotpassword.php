<?php
session_start(); // Ensure session is started

$message = ''; // Initialize the message variable

// Check if there's a message to display
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear message after storing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        <h2>Forgot Password</h2>
        <form action="request_reset.php" method="POST">
            <label for="email">Enter your email address</label>
            <input type="email" name="email" id="email" required>
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
        var message = "<?php echo htmlspecialchars($message, ENT_QUOTES); ?>"; // Use htmlspecialchars for security
        if (message !== "") {
            var popup = document.getElementById('popup');
            document.getElementById('popup-message').textContent = message;

            // If the message indicates an error, add error class
            if (message.includes('Invalid') || message.includes('No account') || message.includes('could not be sent')) {
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
