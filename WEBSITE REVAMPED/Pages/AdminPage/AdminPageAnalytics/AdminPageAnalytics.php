<?php
session_start();
require_once "../../../database/VResortsConnection.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../AdminPage.php");
    exit;
}
$admin_id = $_SESSION['user_id'];

// Fetch properties belonging to the logged-in admin
$sql = "SELECT Property_ID, Name FROM property WHERE Admin_ID = :admin_id ORDER BY Name ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Analytics</title>
    <link rel="stylesheet" href="AdminPageAnalytics.css">
    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="AdminPageAnalytics.js"></script>
    <!-- Include Admin Sidebar CSS -->
    <link rel="stylesheet" href="../AdminSideBar.css">
</head>

<body>
    <?php include "../AdminSideBar.php"; ?>
    <div class="main-content analytics-content">
        <h1>Property Analytics</h1>
        <div class="analytics-filter">
            <label for="propertySelect">Select Property:</label>
            <select id="propertySelect">
                <option value="all">All Properties</option>
                <?php foreach ($properties as $property): ?>
                    <option value="<?php echo htmlspecialchars($property['Property_ID']); ?>">
                        <?php echo htmlspecialchars($property['Name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="groupBy">Group By:</label>
            <select id="groupBy">
                <option value="month" selected>Month</option>
                <option value="year">Year</option>
            </select>
            <button id="loadAnalytics">Load Analytics</button>
        </div>
        <div id="analyticsResults" class="analytics-results">
            <div id="analyticsText">
                <p>Loading analytics...</p>
            </div>
            <!-- Charts Container: All charts side by side -->
            <div id="chartsContainer">
                <div class="chart-wrapper">
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="chart-wrapper">
                    <canvas id="barChart"></canvas>
                </div>
                <!-- Add donut-wrapper here -->
                <div class="chart-wrapper donut-wrapper">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

        </div>
    </div>
    <script>
        // Load overall analytics on page load
        $(document).ready(function() {
            $("#loadAnalytics").trigger("click");
        });
    </script>
</body>

</html>