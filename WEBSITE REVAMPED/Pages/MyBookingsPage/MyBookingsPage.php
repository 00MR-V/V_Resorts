<?php
session_start();
require_once '../../database/VResortsConnection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = 'Please log in to view your bookings.';
    header('Location: /V_Resorts/WEBSITE REVAMPED/Pages/LoginPage/LoginPage.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Fetch all bookings for this user, newest first
$stmt = $pdo->prepare("
    SELECT 
      b.Booking_ID,
      b.Check_In_Date,
      b.Check_Out_Date,
      b.Status,
      p.Name          AS PropertyName,
      p.propertyPhoto AS PhotoBlob
    FROM booking b
    JOIN property p ON b.Property_ID = p.Property_ID
    WHERE b.Customer_ID = :uid
    ORDER BY b.Booking_ID DESC
");
$stmt->execute([':uid' => $userId]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Bookings – V Resorts</title>

  <!-- Page CSS -->
  <link rel="stylesheet" href="MyBookingsPage.css">

  <!-- Site‐wide CSS & JS -->
  <link rel="stylesheet" href="../../components/HeaderComponents/HeaderComponent.css">
  <script defer src="../../components/HeaderComponents/HeaderComponent.js"></script>
  <link rel="stylesheet" href="../../components/LogInModal/LogInModal.css">
  <script defer src="../../components/LogInModal/LogInModal.js"></script>
  <link rel="stylesheet" href="../../components/SignUpModal/SignUpModal.css">
  <script defer src="../../components/SignUpModal/SignUpModal.js"></script>
  <link rel="stylesheet" href="../../components/SignUpModal/AdminSignUpModal.css">
  <script defer src="../../components/SignUpModal/AdminSignUpModal.js"></script>
  <link rel="stylesheet" href="../../components/FooterComponents/FooterComponent.css">
</head>
<body>
  <?php include '../../components/HeaderComponents/HeaderComponent.php'; ?>

  <main class="bookings-container">
    <h1>My Bookings</h1>

    <?php if (empty($bookings)): ?>
      <p class="no-bookings">
        You have no bookings yet.
        <a href="/V_Resorts/WEBSITE REVAMPED/Pages/PropertiesPage/PropertiesPage.php">
          Browse properties &rarr;
        </a>
      </p>
    <?php else: ?>
      <div class="booking-cards">
        <?php foreach ($bookings as $b): 
          // Status badge color
          switch ($b['Status']) {
            case 'Confirmed': $badgeClass = 'badge--green'; break;
            case 'Pending':   $badgeClass = 'badge--orange'; break;
            case 'Cancelled': $badgeClass = 'badge--red'; break;
            case 'Completed': $badgeClass = 'badge--blue'; break;
            default:          $badgeClass = 'badge--gray';
          }
        ?>
          <div class="booking-card">
            <div class="card-image">
              <?php if ($b['PhotoBlob']): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($b['PhotoBlob']) ?>"
                     alt="<?= htmlspecialchars($b['PropertyName']) ?>">
              <?php else: ?>
                <div class="placeholder">No Image</div>
              <?php endif; ?>
            </div>
            <div class="card-content">
              <h2><?= htmlspecialchars($b['PropertyName']) ?></h2>
              <p class="dates">
                <time datetime="<?= $b['Check_In_Date'] ?>">
                  <?= date('M j, Y', strtotime($b['Check_In_Date'])) ?>
                </time>
                &rarr;
                <time datetime="<?= $b['Check_Out_Date'] ?>">
                  <?= date('M j, Y', strtotime($b['Check_Out_Date'])) ?>
                </time>
              </p>
              <span class="status-badge <?= $badgeClass ?>"><?= $b['Status'] ?></span>
              
              <div class="card-actions">
                <?php if ($b['Status'] === 'Pending'): ?>
                  <a href="../SpecificPropertyPage/PaymentPage.php?booking_id=<?= $b['Booking_ID'] ?>"
                     class="btn btn--primary">Pay Now</a>
                  <a href="CancelBooking.php?booking_id=<?= $b['Booking_ID'] ?>"
                     class="btn btn--danger">Cancel</a>
                <?php elseif ($b['Status'] === 'Confirmed'): ?>
                  <a href="BookingDetails.php?booking_id=<?= $b['Booking_ID'] ?>"
                     class="btn btn--secondary">View Details</a>
                <?php elseif ($b['Status'] === 'Completed'): ?>
                  <a href="LeaveReview.php?booking_id=<?= $b['Booking_ID'] ?>"
                     class="btn btn--primary">Leave Review</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>
</body>
</html>
