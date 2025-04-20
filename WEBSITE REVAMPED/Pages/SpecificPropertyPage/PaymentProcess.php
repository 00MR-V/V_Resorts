<?php
// Pages/SpecificPropertyPage/PaymentProcess.php
session_start();
require_once '../../database/VResortsConnection.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: LoginPage.php');
  exit;
}

$bookingId = (int)($_POST['booking_id'] ?? 0);
if (!$bookingId) {
  die("Invalid request.");
}

// Fetch booking & amount again (to guard against tampering)
$stmt = $pdo->prepare("
  SELECT b.Check_In_Date, b.Check_Out_Date, p.Price
  FROM booking b
  JOIN property p ON b.Property_ID = p.Property_ID
  WHERE b.Booking_ID = :bid
    AND b.Customer_ID = :cid
  LIMIT 1
");
$stmt->execute([
  ':bid' => $bookingId,
  ':cid' => $_SESSION['user_id']
]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$info) {
  die("Booking not found or unauthorized.");
}

$ci = new DateTime($info['Check_In_Date']);
$co = new DateTime($info['Check_Out_Date']);
$nights = $ci->diff($co)->days;
$amount = $nights * $info['Price'];

try {
  $pdo->beginTransaction();

  // 1) Insert into payment
  $ins = $pdo->prepare("
    INSERT INTO payment (Booking_ID, Payment_Date, Amount)
    VALUES (:bid, CURDATE(), :amt)
  ");
  $ins->execute([':bid'=>$bookingId, ':amt'=>$amount]);

  // 2) Update booking status
  $upd = $pdo->prepare("
    UPDATE booking
       SET Status = 'Confirmed'
     WHERE Booking_ID = :bid
  ");
  $upd->execute([':bid'=>$bookingId]);

  $pdo->commit();

  // 3) Redirect to confirmation
  header("Location: BookingConfirmation.php?booking_id={$bookingId}");
  exit;

} catch (Exception $e) {
  $pdo->rollBack();
  die("Payment failed. Please try again.");
}
