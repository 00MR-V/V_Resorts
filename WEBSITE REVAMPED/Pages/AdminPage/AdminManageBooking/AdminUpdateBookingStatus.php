<?php
session_start();
require_once "../../../database/VResortsConnection.php";


if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access";
    exit;
}
$admin_id = $_SESSION['user_id'];

$bookingId = isset($_POST['bookingId']) ? trim($_POST['bookingId']) : null;
$newStatus = isset($_POST['newStatus']) ? trim($_POST['newStatus']) : null;

if (!$bookingId || !$newStatus) {
    echo "Invalid parameters";
    exit;
}


$sql = "UPDATE booking b
        JOIN property p ON b.Property_ID = p.Property_ID
        SET b.Status = :newStatus
        WHERE b.Booking_ID = :bookingId
          AND p.Admin_ID = :admin_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':newStatus', $newStatus);
$stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Booking status updated to " . htmlspecialchars($newStatus) . " successfully!";
} else {
    echo "Error updating booking status.";
}
?>
