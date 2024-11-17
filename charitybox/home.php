<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>CharityBox</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>
      /* Reset default browser styles */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      /* Body styling */
      body {
         font-family: Arial, sans-serif;
         background-color: #f3f3f3;
         display: flex;
         flex-direction: column;
         min-height: 100vh;
         margin: 0;
         color: #fff;
      }

      /* Main section styles */
      .main {
         flex: 1;
         width: 100%;
         background-image: url('./images/homebackdrop.jpg');
         background-size: cover;
         background-position: center;
         color: #fff;
         display: flex;
         justify-content: center;
         align-items: center;
         text-align: center;
         position: relative;
         min-height: 120vh;
      }

      /* Overlay for text visibility */
      .main::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background: rgba(0, 0, 0, 0.5);
         z-index: 1;
      }

      /* Text content */
      .main-content {
         position: absolute;
         right: 0;
         z-index: 2;
         max-width: 600px;
         padding: 20px;
         text-align: left;
         top: 50%;
         transform: translateY(-50%);
      }

      .main-content h1 {
         font-size: 36px;
         font-weight: bold;
         margin-bottom: 20px;
      }

      .main-content p {
         font-size: 18px;
         margin-bottom: 30px;
         line-height: 1.6;
      }

      /* Donate button */
      .donate-btn {
         background-color: #ffcc00;
         color: #333;
         padding: 12px 25px;
         border-radius: 50px;
         text-decoration: none;
         font-weight: bold;
         font-size: 16px;
      }

      .donate-btn:hover {
         background-color: #ffd633;
      }

      /* Hide the partners section initially */
/*.hidden {
   display: none;
}

/* Visible class to display the section */
/*.visible {
   display: block;
} */

/* Our Partners Section */
.partners {
   background-color: #fff;
   padding: 50px 20px;
   text-align: center;
}

.partners h2 {
   font-size: 32px;
   color: #333;
   margin-bottom: 40px;
}

.partners-gallery {
   display: flex;
   justify-content: center;
   flex-wrap: wrap;
   gap: 30px;
}

.partner {
   width: 150px;
   text-align: center;
}

.partner img {
   width: 100%;
   height: auto;
   border-radius: 8px;
   box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.partner p {
   margin-top: 10px;
   font-size: 16px;
   color: #333;
   font-weight: 500;
}

   </style>
</head>
<body>

<?php include 'header.php'; ?>
   <!-- Main Section -->
   <section class="main">
      <div class="main-content">
         <h1>Happiness comes from your action.</h1>
         <p>Be a part of the breakthrough and make someone's dream come true.</p>
         <a href="organization_list.php" class="donate-btn">Donate now</a>
      </div>
   </section>

   <section class="partners hidden">
      <h2>Our Partners</h2>
      <div class="partners-gallery">
         <div class="partner">
            <img src="./images/hands.png" alt="Partner 1">
            <p>Hands</p>
         </div>
         <div class="partner">
            <img src="./images/org2.png" alt="Partner 2">
            <p>Patient's Welfare Association</p>
         </div>
         <div class="partner">
            <img src="./images/akhuwat.png" alt="Partner 3">
            <p>Akhuwat Foundation</p>
         </div>
         <div class="partner">
            <img src="./images/chippa.png" alt="Partner 3">
            <p>Chhipa Welfare Association</p>
         </div>
        
         <!-- Add more partners as needed -->
      </div>
   </section>
   
   <?php include 'footer.php'; ?>
</body>
</html>
