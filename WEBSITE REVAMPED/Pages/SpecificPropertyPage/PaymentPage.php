<?php
// Pages/SpecificPropertyPage/PaymentPage.php
session_start();
require_once '../../database/VResortsConnection.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: LoginPage.php');
  exit;
}

$bookingId = (int)($_GET['booking_id'] ?? 0);
if (!$bookingId) {
  die("Booking not found.");
}

// Fetch booking + property
$stmt = $pdo->prepare("
  SELECT b.Check_In_Date, b.Check_Out_Date, p.Name, p.Price
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

// Calculate nights & amount
$ci = new DateTime($info['Check_In_Date']);
$co = new DateTime($info['Check_Out_Date']);
$nights = $ci->diff($co)->days;
$amount = $nights * $info['Price'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment – V Resorts</title>
  <style>
    body { font-family: sans-serif; padding:2rem; background:#f9f9f9; }
    .card { background:#fff; padding:1.5rem; max-width:400px; margin:auto; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
    h1 { margin-top:0; }
    .field { margin:1rem 0; }
    .label { font-weight:500; color:#555; }
    .value { font-size:1.1rem; }
    button { padding:0.75rem 1.5rem; background:#3f51b5; color:#fff; border:none; border-radius:4px; cursor:pointer; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Complete Payment</h1>
    <div class="field">
      <div class="label">Property</div>
      <div class="value"><?= htmlspecialchars($info['Name']) ?></div>
    </div>
    <div class="field">
      <div class="label">Dates</div>
      <div class="value">
        <?= $ci->format('M j, Y') ?> – <?= $co->format('M j, Y') ?> (<?= $nights ?> nights)
      </div>
    </div>
    <div class="field">
      <div class="label">Amount Due</div>
      <div class="value"><strong>Rs. <?= number_format($amount) ?></strong></div>
    </div>
    <form action="PaymentProcess.php" method="POST">
      <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
      <button type="submit">Pay Now</button>
    </form>
  </div>
</body>
</html>
