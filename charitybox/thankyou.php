<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    
    <style>
        /* General Styles */
        * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
        }
        body {
         font-family: Arial, sans-serif;
         background-color: #f3f3f3;
         color: #333;
         display: flex;
         flex-direction: column;
         min-height: 100vh;
         margin: 0;
        }

        .container {
            text-align: center;
            animation: fadeIn 2s ease-in-out;
            flex: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            max-width: 8000px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3rem;
            margin: 0 0 10px;
            animation: slideDown 1s ease-out;
        }

        p {
            font-size: 1.5rem;
            margin: 10px 0;
            animation: fadeIn 3s ease-in-out;
        }

        .button-container1 {
            margin-top: 20px;
        }

        .button-container1 a {
            display: inline-block;
            padding: 12px 25px;
            text-decoration: none;
            background-color: #504434;
            color: #fff;
            font-weight: bold;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .button-container1 a:hover{
            background-color: #ffcc00;
            color: #000;
        }

        /* Animation Styles */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Floating Animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    
    <?php include 'header.php'; ?>
    
    <section class="container">
        <h1 class="floating">Thank You for Your Donation!</h1>
        <p>We sincerely appreciate your generosity and will contact you shortly.</p>
        <div class="button-container1">
            <a href="home.php">Go to Home</a>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
