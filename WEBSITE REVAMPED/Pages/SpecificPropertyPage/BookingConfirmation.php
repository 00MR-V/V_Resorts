<?php
session_start();
require_once '../../database/VResortsConnection.php';

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
if (!$bookingId) {
    die("Booking not found.");
}


$stmt = $pdo->prepare("
    SELECT b.Booking_ID, b.Check_In_Date, b.Check_Out_Date, b.Status,
           p.Name, p.Location, p.Price
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
$booking = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$booking) {
    die("Booking not found or you do not have permission to view it.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Booking Confirmation – V Resorts</title>
  <style>
    body { font-family:sans-serif; background:#f9f9f9; padding:2rem; }
    .card { background:#fff; max-width:500px; margin:auto; padding:1.5rem; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
    h1 { margin-top:0; color:#3f51b5; }
    .field { margin:0.75rem 0; }
    .label { font-weight:500; color:#555; }
    .value { font-size:1.1rem; }
    .status { text-transform:uppercase; font-weight:bold; color:#ff9800; }
    .btn { display:inline-block; margin-top:1rem; padding:0.75rem 1.5rem; background:#3f51b5; color:#fff; text-decoration:none; border-radius:4px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Booking #<?= htmlspecialchars($booking['Booking_ID']) ?> Confirmed</h1>
    <div class="field">
      <div class="label">Property</div>
      <div class="value"><?= htmlspecialchars($booking['Name']) ?> — <?= htmlspecialchars($booking['Location']) ?></div>
    </div>
    <div class="field">
      <div class="label">Dates</div>
      <div class="value">
        <?= date('M j, Y', strtotime($booking['Check_In_Date'])) ?> 
        &ndash; 
        <?= date('M j, Y', strtotime($booking['Check_Out_Date'])) ?>
      </div>
    </div>
    <div class="field">
      <div class="label">Nights</div>
      <div class="value">
        <?= round((strtotime($booking['Check_Out_Date']) - strtotime($booking['Check_In_Date']))/86400) ?>
      </div>
    </div>
    <div class="field">
      <div class="label">Subtotal</div>
      <div class="value">
        Rs. <?= number_format($booking['Price'] * round((strtotime($booking['Check_Out_Date']) - strtotime($booking['Check_In_Date']))/86400)) ?>
      </div>
    </div>
    <div class="field">
      <div class="label">Status</div>
      <div class="value status"><?= htmlspecialchars($booking['Status']) ?></div>
    </div>
    <a href="../../Pages/MyBookingsPage/MyBookingsPage.php" class="btn">View My Bookings</a>
  </div>
</body>
</html>
