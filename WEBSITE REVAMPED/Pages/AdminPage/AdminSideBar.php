<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = 'Admin User';
}
?>

<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="admin-info">
        <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>
    </div>
    <ul class="nav-list">
        <li><a href="/V_Resorts/WEBSITE%20REVAMPED/Pages/AdminPage/AdminPage.php" data-section="dashboard">Dashboard</a></li>
        <li><a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageProperties/AdminManageProperties.php">Manage Properties</a></li>
        <li><a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminManageBooking/AdminManageBooking.php">Manage Bookings</a></li>
        <li><a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPageAnalytics\AdminPageAnalytics.php">Analytics</a></li>
        <li><a href="/V_Resorts/WEBSITE%20REVAMPED/Pages/HomePage/HomePage.php">Back to User Home Page</a></li>
    </ul>
</div>