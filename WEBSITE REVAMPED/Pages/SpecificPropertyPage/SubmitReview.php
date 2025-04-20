<?php
session_start();
require_once '../../database/VResortsConnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header("Location: SpecificPropertyPage.php?property_id=" . urlencode($_POST['property_id'] ?? ''));
    exit;
}

$custId     = $_SESSION['user_id'];
$propertyId = (int)($_POST['property_id'] ?? 0);
$bookingId  = (int)($_POST['booking_id'] ?? 0);
$comment    = trim($_POST['comment'] ?? '');

if (!$propertyId || !$bookingId || $comment === '') {
    die("Missing data.");
}

// 1. Verify booking belongs to this user & property, and is completed (Check_Out < today)
$stmt = $pdo->prepare("
    SELECT 1
    FROM booking
    WHERE Booking_ID = :bid
      AND Customer_ID = :cid
      AND Property_ID = :pid
      AND Check_Out_Date < CURDATE()
    LIMIT 1
");
$stmt->execute([
    ':bid' => $bookingId,
    ':cid' => $custId,
    ':pid' => $propertyId
]);
if (!$stmt->fetch()) {
    die("Invalid booking.");
}

// 2. Ensure no existing review for this booking
$chk = $pdo->prepare("SELECT 1 FROM review WHERE Booking_ID = :bid");
$chk->execute([':bid' => $bookingId]);
if ($chk->fetch()) {
    die("You’ve already reviewed this stay.");
}

// 3. Insert the review
$ins = $pdo->prepare("
    INSERT INTO review (Booking_ID, Review_Date, Comment)
    VALUES (:bid, CURDATE(), :comment)
");
$ins->execute([
    ':bid'     => $bookingId,
    ':comment' => $comment
]);

// Redirect back
header("Location: SpecificPropertyPage.php?property_id={$propertyId}#reviews");
exit;
