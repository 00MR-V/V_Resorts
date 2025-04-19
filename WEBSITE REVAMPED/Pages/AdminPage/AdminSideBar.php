<?php
// Pages/AdminPage/AdminSideBar.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If the admin is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../AdminPage.php");
    exit;
}

// Get the admin's ID from the session
$adminId = (int) $_SESSION['user_id'];

// Include database connection
require_once __DIR__ . "/../../database/VResortsConnection.php";

try {
    // Fetch the admin's first and last name
    $stmt = $pdo->prepare("
        SELECT FName, LName 
        FROM adminn 
        WHERE Admin_Id = :id 
        LIMIT 1
    ");
    $stmt->execute([':id' => $adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fallback to generic if not found
    if ($admin) {
        $greetingName = htmlspecialchars($admin['FName'] . ' ' . $admin['LName']);
    } else {
        $greetingName = 'Admin';
    }
} catch (PDOException $e) {
    // On error just say “Admin”
    $greetingName = 'Admin';
}
?>

<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="admin-info">
        <h2>Welcome Admin <?php echo $greetingName; ?></h2>
    </div>
    <ul class="nav-list">
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPage.php" data-section="dashboard">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageProperties/AdminManageProperties.php">
                Manage Properties
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageBooking/AdminManageBooking.php">
                Manage Bookings
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPageAnalytics/AdminPageAnalytics.php">
                Analytics
            </a>
        </li>
        <li>
            <a href="/V_Resorts/WEBSITE REVAMPED/Pages/HomePage/HomePage.php">
                Back to User Home Page
            </a>
        </li>
    </ul>
</div>