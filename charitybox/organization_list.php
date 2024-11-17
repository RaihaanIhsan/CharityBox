<?php
require_once 'DatabaseConnection.php'; // Include the DatabaseConnection class

// Initialize the database connection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Initialize variables for filters
$filter_location = '';
$filter_category = '';
$search = '';
$limit = 6;  // Limit to 5 organizations when no filter is applied

// Capture filter inputs if the user applies them
if (isset($_POST['filter'])) {
    $filter_location = $_POST['location'];
    $filter_category = $_POST['category'];
    $limit = '';  // Show all matching organizations when a filter is applied
}

// Capture search input if the user searches
if (isset($_POST['search'])) {
    $search = $_POST['search_query'];
    $limit = '';  // Show all matching organizations when a search is performed
}

// Build the SQL query to fetch organizations
$sql = "SELECT * FROM organizations WHERE 1=1";  // Basic query

// Add filtering logic
if ($filter_location != '') {
    $sql .= " AND location LIKE '%$filter_location%'";
}

if ($filter_category != '') {
    $sql .= " AND category LIKE '%$filter_category%'";
}

// Add search logic
if ($search != '') {
    $sql .= " AND (location LIKE '%$search%' OR category LIKE '%$search%')";
}

// Limit results to 5 organizations by default
if ($limit != '') {
    $sql .= " LIMIT $limit";
}

// Fetch the organizations
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CharityBox - Organization List</title>
    <link rel="stylesheet" href="./styles.css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function toggleFilterPanel() {
            const filterPanel = document.getElementById("filterPanel");
            const contentContainer = document.getElementById("contentContainer");
            filterPanel.classList.toggle("open");
            contentContainer.classList.toggle("open-panel");
        }
        function clearFilters() {
            document.querySelector('select[name="category"]').selectedIndex = 0; // Reset category to 'All Categories'
            document.getElementById("location").value = ""; // Clear location input
            document.forms[0].submit(); // Submit the form to reload with default list
        }
    </script>
    
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(function() {
            // Auto-complete for location input
            var locations = [
                "Karachi", "Karachi - Clifton", "Karachi - DHA", "Karachi - Gulshan-e-Iqbal",
                "Lahore", "Lahore - Model Town", "Lahore - Gulberg", "Lahore - Johar Town",
                "Islamabad", "Islamabad - F-7", "Islamabad - F-10", "Islamabad - G-11",
                "Rawalpindi", "Rawalpindi - Saddar", "Rawalpindi - DHA", "Peshawar", "Multan",
                "Quetta", "Faisalabad"
            ];

            $("#location").autocomplete({
                source: locations
            });
        });
    </script>
   
</head>

<body>
<?php include 'header.php'; ?>
<button class="filter-button" onclick="toggleFilterPanel()">
    <i class="fas fa-filter"></i> Filter
</button>

<div class="filter-panel" id="filterPanel">
    <button class="close-button" onclick="toggleFilterPanel()">X</button>
    
    <form action="organization_list.php" method="POST">
        <select name="category">
            <option value="">All Categories</option>
            <option value="Food" <?php if (isset($filter_category) && $filter_category == 'Food') echo 'selected'; ?>>Food</option>
            <option value="Clothes" <?php if (isset($filter_category) && $filter_category == 'Clothes') echo 'selected'; ?>>Clothes</option>
            <option value="Toys" <?php if (isset($filter_category) && $filter_category == 'Toys') echo 'selected'; ?>>Toys</option>
        </select>
        <!-- Location input with auto-complete -->
        <input type="text" id="location" name="location" placeholder="Type or choose a location" value="<?php echo isset($filter_location) ? $filter_location : ''; ?>">
        <input type="submit" name="filter" value="Filter">
        <button type="button" onclick="clearFilters()">Clear Filter</button>
    </form>
</div>

   
    <div class="container open-panel" id="contentContainer"> 
        <div class="organization-list">
            <div class="cards-container">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($org = mysqli_fetch_assoc($result)) {
                        echo "<div class='organization-card'>";
                        echo "<img src='./images/". $org['image'] . "' alt='" . $org['name'] . " Image' class='org-image'>";
                        // echo "<h3>" . $org['name'] . "</h3>";
                        echo "<p><strong>Donations Accepted:</strong> " . $org['category'] . "</p>";
                        echo "<p><strong>Location:</strong> " . $org['location'] . "</p>";
                        echo "<p>" . $org['mission_statement'] . "</p>";
                        echo "<p><strong>Contact Info:</strong> " . $org['contact_info'] . "</p>";
                        echo "<a href='login.php?org_id=" . $org['id'] . "' class='donate-button'>Donate Now</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No organizations found matching your criteria.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
            