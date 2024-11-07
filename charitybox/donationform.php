<?php
session_start();
include 'config.php';

// Check if user is logged in and org_id is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['org_id'])) {
    // Redirect to login if either user_id or org_id is missing
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$org_id = $_SESSION['org_id'];

// Retrieve organization name
$sql = "SELECT name FROM organizations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$stmt->bind_result($org_name);
$stmt->fetch();
$stmt->close();

// Handle form submission within this page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $condition = $_POST['condition'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $image_paths = [];

    // Handle file upload
    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        foreach ($_FILES['images']['name'] as $key => $image_name) {
            $temp_name = $_FILES['images']['tmp_name'][$key];
            $image_path = "uploads/" . basename($image_name);
            if (move_uploaded_file($temp_name, $image_path)) {
                $image_paths[] = $image_path;
            }
        }
    }
    $image_path_string = implode(',', $image_paths);

    // Insert record
    $sql = "INSERT INTO donations (user_id, org_id, item_description, image_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $user_id, $org_id, $description, $image_path_string);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Donation form submitted successfully!';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>CharityBox - Donation Form</title>
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
         color: #333;
         display: flex;
         flex-direction: column;
         min-height: 100vh;
         margin: 0;
      }

     
      /* Form container */
      .form-container {
         flex: 1;
         padding: 40px;
         max-width: 800px;
         margin: 40px auto;
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .form-container h2 {
         text-align: center;
         margin-bottom: 20px;
         color: #534C3C;
      }

      .form-group {
         margin-bottom: 15px;
      }

      .form-group label {
         display: block;
         font-weight: bold;
         margin-bottom: 5px;
      }

      .form-group input, .form-group select, .form-group textarea {
         width: 100%;
         padding: 10px;
         border: 1px solid #ddd;
         border-radius: 5px;
      }

      .form-group textarea {
         resize: vertical;
      }

      .form-group small {
         color: #666;
      }

      /* Button */
      .submit-btn {
         display: block;
         width: 100%;
         background-color: #ffcc00;
         color: #333;
         padding: 12px;
         border: none;
         border-radius: 5px;
         font-weight: bold;
         cursor: pointer;
         text-align: center;
      }

      .submit-btn:hover {
         background-color: #ffd633;
      }

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
            background-color: #f44336;
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

<section class="form-container">
   <h2>Donate to <?php echo htmlspecialchars($org_name); ?></h2>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="org_id" value="<?php echo $org_id; ?>">
      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

      <!-- Form Fields -->
      <div class="form-group">
         <label for="title">Item Title</label>
         <input type="text" id="title" name="title" required>
      </div>
      <div class="form-group">
         <label for="category">Item Category</label>
         <select id="category" name="category" required>
            <option value="clothing">Clothing</option>
            <option value="food">Food</option>
            <option value="toys">Toys</option>
            <option value="other">Other</option>
         </select>
      </div>
      <div class="form-group">
         <label>Condition</label>
         <select id="condition" name="condition" required>
            <option value="new">New</option>
            <option value="gently-used">Gently Used</option>
            <option value="used">Used</option>
            <option value="damaged">Can't Be Specified</option>
         </select>
      </div>
      <div class="form-group">
         <label for="description">Description</label>
         <textarea id="description" name="description" rows="4"></textarea>
      </div>
      <div class="form-group">
         <label for="quantity">Quantity</label>
         <input type="number" id="quantity" name="quantity" min="1" required>
      </div>
      <div class="form-group">
         <label for="location">Location</label>
         <input type="text" id="location" name="location" required>
      </div>
      <div class="form-group">
         <label for="contact">Contact Information</label>
         <input type="text" id="contact" name="contact" required>
      </div>
      <div class="form-group">
         <label for="images">Upload Images</label>
         <input type="file" id="images" name="images[]" accept="image/*" multiple>
         <small>You can upload up to 5 images.</small>
      </div>
      <button type="submit" class="submit-btn">Submit Donation</button>
   </form>
   
   <!-- Popup Message -->
   <div id="popup" class="popup">
      <span id="popup-message"></span>
      <button class="close-btn" onclick="hidePopup()">✕</button>
   </div>

   <script>
      window.onload = function() {
         var message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
         if (message !== "") {
            var popup = document.getElementById('popup');
            document.getElementById('popup-message').textContent = message;
            if (message.includes('Error')) {
               popup.classList.add('error');
            }
            popup.classList.add('show');
            <?php unset($_SESSION['message']); ?>
         }
      };

      function hidePopup() {
         var popup = document.getElementById('popup');
         popup.classList.remove('show');
      }
   </script>
</section>
<?php include 'footer.php'; ?>
</body>
</html>
