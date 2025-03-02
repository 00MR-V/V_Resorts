<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = 'Admin User';
    $_SESSION['user_id'] = 1; // Set the admin ID here so that your queries in AdminGetProperty.php, AdminSaveProperty.php, etc. work properly.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../Pages/AdminPage//AdminSideBar.css">
    <script src="../../Pages/AdminPage/AdminSideBar.js"></script>
</head>

<body>
    <!-- Admin Sidebar -->
    <?php include "AdminSidebar.php"; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div id="dashboard" class="content-section active">
            <h1>Admin Dashboard</h1>
            <p>Welcome to the Admin Panel. Below is a detailed guide on how to manage different sections of the website.</p>
            <h2>Admin Guide</h2>
            <ul class="admin-manual">
                <li><strong>Dashboard:</strong> The overview page displaying website statistics, recent activity, and key insights into user engagement.</li>
                <li><strong>Manage Properties:</strong>
                    <ul>
                        <li>Add new properties by filling in property details like name, location, price, and images.</li>
                        <li>Edit existing properties to update information.</li>
                        <li>Delete properties that are no longer available.</li>
                    </ul>
                </li>
                <li><strong>Manage Bookings:</strong>
                    <ul>
                        <li>View all current and past bookings made by users.</li>
                        <li>Update booking statuses (confirmed, pending, canceled).</li>
                        <li>Send notifications or emails to customers about booking changes.</li>
                    </ul>
                </li>
                <li><strong>Manage Users:</strong>
                    <ul>
                        <li>View registered users and their details.</li>
                        <li>Disable or remove inactive or problematic accounts.</li>
                        <li>Reset passwords for users upon request.</li>
                    </ul>
                </li>
                <li><strong>Analytics:</strong>
                    <ul>
                        <li>Monitor user activity, including most visited pages.</li>
                        <li>View booking trends and property popularity.</li>
                        <li>Export reports for business analysis.</li>
                    </ul>
                </li>
            </ul>
            <p><strong>Note:</strong> Regularly update listings, verify user details, and monitor analytics to ensure smooth operations.</p>
        </div>

        <div id="properties" class="content-section hidden">
            <h1>Manage Properties</h1>
            <p>View, edit, and delete properties.</p>
            <button>Add New Property</button>
        </div>

        <div id="bookings" class="content-section hidden">
            <h1>Manage Bookings</h1>
            <p>View and manage all bookings.</p>
        </div>

        <div id="users" class="content-section hidden">
            <h1>Manage Users</h1>
            <p>View and manage all users.</p>
        </div>

        <div id="analytics" class="content-section hidden">
            <h1>Analytics</h1>
            <p>View detailed system analytics.</p>
        </div>
    </div>
</body>

</html>
