<?php
session_start();
require_once 'DatabaseConnection.php'; // Include the DatabaseConnection class

// Initialize the database connection
$db = new DatabaseConnection();
$conn = $db->getConnection();

require_once 'helpers.php'; // Include helper functions

// Redirect if not logged in
if (!isset($_SESSION['user_id'], $_SESSION['org_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$org_id = $_SESSION['org_id'];

// Fetch organization name
$org_name = fetchOrganizationName($conn, $org_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$title || !$category || !$condition || !$quantity || !$location || !$contact) {
        $_SESSION['message'] = 'All fields are required.';
        header("Location: donation_form.php");
        exit();
    }

    // Handle file uploads
    $image_paths = handleFileUploads($_FILES['images'], 'uploads/', ['jpg', 'png', 'jpeg'], 5);

    // Insert data into the database
    $image_path_string = implode(',', $image_paths);
    $stmt = $conn->prepare("INSERT INTO donations (user_id, org_id, title, category, `condition`, item_description, quantity, location, contact, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssisss", $user_id, $org_id, $title, $category, $condition, $description, $quantity, $location, $contact, $image_path_string);
    $status = 'Pending';  // Default value for status
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Donation submitted successfully!';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
    }
    $stmt->close();
    header("Location: donationform.php");
    exit();
}
