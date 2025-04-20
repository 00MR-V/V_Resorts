<?php
session_start();
require_once '../../database/VResortsConnection.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = 'Please log in to cancel bookings.';
    header('Location: /V_Resorts/WEBSITE REVAMPED/Pages/LoginPage/LoginPage.php');
    exit;
}

$bookingId = (int)($_GET['booking_id'] ?? 0);
$userId    = (int)$_SESSION['user_id'];

if (!$bookingId) {
    die("Invalid booking.");
}

// 1) Verify ownership
$stmt = $pdo->prepare("
  SELECT Status
    FROM booking
   WHERE Booking_ID = :bid
     AND Customer_ID = :cid
   LIMIT 1
");
$stmt->execute([
  ':bid' => $bookingId,
  ':cid' => $userId
]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found or unauthorized.");
}

if ($booking['Status'] === 'Cancelled') {
    $_SESSION['error_msg'] = 'That booking is already cancelled.';
    header('Location: MyBookingsPage.php');
    exit;
}

// 2) Mark as cancelled
$upd = $pdo->prepare("
  UPDATE booking
     SET Status = 'Cancelled'
   WHERE Booking_ID = :bid
");
$upd->execute([':bid' => $bookingId]);

$_SESSION['success_msg'] = 'Your booking has been cancelled.';
header('Location: MyBookingsPage.php');
exit;
