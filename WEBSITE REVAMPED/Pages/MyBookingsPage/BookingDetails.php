<?php
session_start();
require_once '../../database/VResortsConnection.php';

// 1) Ensure logged in
$show_search_box = false;
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = 'Please log in to view booking details.';
    header('Location: /V_Resorts/WEBSITE REVAMPED/Pages/LoginPage/LoginPage.php');
    exit;
}

$bookingId = (int)($_GET['booking_id'] ?? 0);
$userId    = (int)$_SESSION['user_id'];
if (!$bookingId) {
    die("Invalid booking reference.");
}

// 2) Fetch booking + property + payment summary (including Admin_ID)
$stmt = $pdo->prepare("
  SELECT 
    b.Booking_ID,
    b.Check_In_Date,
    b.Check_Out_Date,
    b.Status,
    p.Property_ID,
    p.Admin_ID        AS OwnerId,
    p.Name            AS PropertyName,
    p.Type            AS PropertyType,
    p.Location        AS PropertyLocation,
    p.Capacity        AS PropertyCapacity,
    p.Price           AS PricePerNight,
    COALESCE(SUM(pay.Amount),0) AS TotalPaid
  FROM booking b
  JOIN property p ON p.Property_ID = b.Property_ID
  LEFT JOIN payment pay ON pay.Booking_ID = b.Booking_ID
  WHERE b.Booking_ID = :bid
    AND b.Customer_ID = :cid
  GROUP BY b.Booking_ID
");
$stmt->execute([
  ':bid' => $bookingId,
  ':cid' => $userId
]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$info) {
    die("Booking not found or access denied.");
}

// 3) Fetch customer details
$custStmt = $pdo->prepare("
  SELECT FName, LName, Email, Phone_Num, Address 
  FROM customers 
  WHERE Cust_Id = :cid
  LIMIT 1
");
$custStmt->execute([':cid' => $userId]);
$customer = $custStmt->fetch(PDO::FETCH_ASSOC);

// 4) Fetch owner (admin) details
$ownerStmt = $pdo->prepare("
  SELECT FName, LName, Email, Phone_Num, Address 
  FROM adminn 
  WHERE Admin_Id = :aid
  LIMIT 1
");
$ownerStmt->execute([':aid' => $info['OwnerId']]);
$owner = $ownerStmt->fetch(PDO::FETCH_ASSOC);

// 5) Calculate derived values
$ci     = new DateTime($info['Check_In_Date']);
$co     = new DateTime($info['Check_Out_Date']);
$nights = $ci->diff($co)->days;
$total  = $nights * $info['PricePerNight'];
$balance= $total - $info['TotalPaid'];

// 6) Status badge class
$statusClass = match($info['Status']) {
  'Pending'   => 'status--pending',
  'Confirmed' => 'status--confirmed',
  'Completed' => 'status--completed',
  'Cancelled' => 'status--cancelled',
  default     => 'status--gray'
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Booking #<?= htmlspecialchars($info['Booking_ID']) ?> – V Resorts</title>

  <!-- Global styles -->
  <link rel="stylesheet" href="../../components/HeaderComponents/HeaderComponent.css">
  <link rel="stylesheet" href="../../components/LogInModal/LogInModal.css">
  <link rel="stylesheet" href="../../components/SignUpModal/SignUpModal.css">
  <link rel="stylesheet" href="../../components/FooterComponents/FooterComponent.css">

  <!-- Page styles -->
  <link rel="stylesheet" href="BookingDetails.css">
</head>
<body>

  <main class="details-container">
    <div class="card">

      <!-- Header -->
      <header class="card-header">
        <h1>Booking #<?= $info['Booking_ID'] ?></h1>
        <span class="status <?= $statusClass ?>">
          <?= htmlspecialchars(strtoupper($info['Status'])) ?>
        </span>
      </header>

      <div class="card-body">

        <!-- Customer Details -->
        <section class="section">
          <h3 class="section-heading">Customer Details</h3>
          <div class="section-content">
            <p><strong>Name:</strong> <?= htmlspecialchars($customer['FName'] . ' ' . $customer['LName']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($customer['Email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($customer['Phone_Num']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($customer['Address']) ?></p>
          </div>
        </section>

        <!-- Owner (Admin) Details -->
        <section class="section">
          <h3 class="section-heading">Owner Details</h3>
          <div class="section-content">
            <p><strong>Name:</strong> <?= htmlspecialchars($owner['FName'] . ' ' . $owner['LName']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($owner['Email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($owner['Phone_Num']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($owner['Address']) ?></p>
          </div>
        </section>

        <!-- Property & Booking Details -->
        <section class="section property-info">
          <h3 class="section-heading">Property &amp; Stay Info</h3>
          <div class="section-content">
            <p><strong>Property:</strong> <?= htmlspecialchars($info['PropertyName']) ?> (<?= htmlspecialchars($info['PropertyType']) ?>)</p>
            <p><strong>Location:</strong> <?= htmlspecialchars($info['PropertyLocation']) ?></p>
            <p><strong>Capacity:</strong> <?= htmlspecialchars($info['PropertyCapacity']) ?></p>
            <p>
              <strong>Dates:</strong>
              <time datetime="<?= $info['Check_In_Date'] ?>"><?= $ci->format('M j, Y') ?></time>
              &rarr;
              <time datetime="<?= $info['Check_Out_Date'] ?>"><?= $co->format('M j, Y') ?></time>
              (<?= $nights ?> nights)
            </p>
          </div>
        </section>

        <!-- Payment Summary -->
        <section class="section">
          <h3 class="section-heading">Payment Summary</h3>
          <div class="section-content">
            <p><strong>Price/night:</strong> Rs. <?= number_format($info['PricePerNight']) ?></p>
            <p><strong>Total due:</strong> Rs. <?= number_format($total) ?></p>
            <p><strong>Paid so far:</strong> Rs. <?= number_format($info['TotalPaid']) ?></p>
            <p><strong>Balance:</strong> Rs. <?= number_format($balance) ?></p>
          </div>
        </section>
      </div>

      <!-- Footer actions -->
      <footer class="card-footer">
        <?php if ($balance > 0 && $info['Status'] === 'Pending'): ?>
          <a href="../SpecificPropertyPage/PaymentPage.php?booking_id=<?= $info['Booking_ID'] ?>"
             class="btn btn--primary">Pay Now</a>
        <?php endif; ?>
        <a href="../MyBookingsPage/MyBookingsPage.php" class="btn btn--secondary">Back to My Bookings</a>
      </footer>

    </div>
  </main>


</body>
</html>
