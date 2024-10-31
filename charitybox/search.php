<?php
include 'config.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

$sql = "SELECT * FROM organizations WHERE 1=1";

// Add category filter if set
if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

// Add location filter if set
if (!empty($location)) {
    $sql .= " AND location LIKE '%$location%'";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($org = mysqli_fetch_assoc($result)) {
        echo "<h3>{$org['name']}</h3>";
        echo "<p>Category: {$org['category']}</p>";
        echo "<p>Location: {$org['location']}</p>";
        echo "<p>Contact: {$org['contact_info']}</p>";
        echo "<p>Status: " . ($org['status'] ? 'Accepting Donations' : 'Not Accepting Donations') . "</p>";
        echo "<hr>";
    }
} else {
    echo "No organizations found.";
}
?>
