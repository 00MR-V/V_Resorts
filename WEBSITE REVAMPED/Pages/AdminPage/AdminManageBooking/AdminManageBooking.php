<?php
session_start();
require_once "../../../database/VResortsConnection.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../AdminPage.php");
    exit;
}

$admin_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>

    <!-- Main CSS for Manage Bookings -->
    <link rel="stylesheet" href="AdminManageBooking.css">

    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Main JS for Manage Bookings -->
    <script src="AdminManageBooking.js"></script>

    <!-- Include Admin Sidebar CSS (Adjust path if needed) -->
    <link rel="stylesheet" href="../AdminSideBar.css">
</head>
<body>
    <?php include "../AdminSideBar.php"; ?>

    <div class="main-content">
        <h1>Manage Bookings</h1>

        <!-- Optional Filter Form -->
        <form id="filterForm" class="filter-container">
            <input type="text" name="search" placeholder="Search by customer or property">
            <button type="submit">Filter</button>
        </form>

        <!-- Bookings Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Property</th>
                        <th>Customer</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody">
                    <tr><td colspan="8">Loading bookings...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="modal hidden">
        <div class="modal-content">
            <!-- Content will be populated dynamically via AJAX -->
        </div>
    </div>
</body>
</html>
