<?php
session_start();
require_once '../../database/VResortsConnection.php';


$show_search_box = false;


$property_id = isset($_GET['property_id']) ? (int)$_GET['property_id'] : null;
if (!$property_id) {
    die("Property not found!");
}


$stmt = $pdo->prepare("SELECT * FROM property WHERE Property_ID = :pid");
$stmt->execute([':pid' => $property_id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$property) {
    die("Property not found!");
}


$reviewStmt = $pdo->prepare("
    SELECT r.Review_Date, r.Comment, c.Username
    FROM review r
    JOIN booking b ON r.Booking_ID = b.Booking_ID
    JOIN customers c ON b.Customer_ID = c.Cust_Id
    WHERE b.Property_ID = :pid
    ORDER BY r.Review_Date DESC
");
$reviewStmt->execute([':pid' => $property_id]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);


$eligibleBookings = [];
if (isset($_SESSION['user_id'])) {
    $pendingStmt = $pdo->prepare("
        SELECT Booking_ID, Check_In_Date, Check_Out_Date
        FROM booking
        WHERE Customer_ID = :cid
          AND Property_ID = :pid
          AND Check_Out_Date < CURDATE()
          AND Booking_ID NOT IN (SELECT Booking_ID FROM review)
    ");
    $pendingStmt->execute([
        'cid' => $_SESSION['user_id'],
        'pid' => $property_id
    ]);
    $eligibleBookings = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);
}


$disableStmt = $pdo->prepare("
    SELECT Check_In_Date, Check_Out_Date
    FROM booking
    WHERE Property_ID = :pid
      AND Status != 'Cancelled'
");
$disableStmt->execute([':pid' => $property_id]);
$disabledRanges = array_map(function($r){
    return [$r['Check_In_Date'], $r['Check_Out_Date']];
}, $disableStmt->fetchAll(PDO::FETCH_ASSOC));
$disabledJson = htmlspecialchars(json_encode($disabledRanges), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($property['Name']) ?> – V Resorts</title>

 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  
  <link rel="stylesheet" href="../../components/HeaderComponents/HeaderComponent.css">
  <link rel="stylesheet" href="../../components/LogInModal/LogInModal.css">
  <link rel="stylesheet" href="../../components/SignUpModal/SignUpModal.css">
  <link rel="stylesheet" href="../../components/SignUpModal/AdminSignUpModal.css">
  <link rel="stylesheet" href="../../components/FooterComponents/FooterComponent.css">

  
  <link rel="stylesheet" href="SpecificPropertyPage.css">

 
  <script defer src="../../components/HeaderComponents/HeaderComponent.js"></script>
  <script defer src="../../components/LogInModal/LogInModal.js"></script>
  <script defer src="../../components/SignUpModal/SignUpModal.js"></script>
  <script defer src="../../components/SignUpModal/AdminSignUpModal.js"></script>

 
  <script defer src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
  <?php include '../../components/HeaderComponents/HeaderComponent.php'; ?>

  <main class="property-details">

    <div class="left-col">
      <div class="gallery">
        <?php if (!empty($property['propertyPhoto'])): ?>
          <img
            src="data:image/jpeg;base64,<?= base64_encode($property['propertyPhoto']) ?>"
            alt="Main Photo"
          >
        <?php endif; ?>

        <?php foreach (json_decode($property['Gallery_Photos'], true) ?: [] as $img): ?>
          <img src="../../images/properties/<?= htmlspecialchars($img) ?>" alt="Gallery Photo">
        <?php endforeach; ?>
      </div>

      <aside class="booking-section">
        <h2>Book This Property</h2>
        <form id="bookingForm" action="BookProperty.php" method="POST">
          <input type="hidden" name="property_id" value="<?= $property_id ?>">
          <input type="hidden" id="checkInInput" name="check_in">
          <input type="hidden" id="checkOutInput" name="check_out">

         
          <div
            id="calendar"
            data-price="<?= $property['Price'] ?>"
            data-disabled='<?= $disabledJson ?>'
          ></div>

          <div class="date-summary">
            <div>Check‑In: <span id="ciDisplay">—</span></div>
            <div>Check‑Out: <span id="coDisplay">—</span></div>
            <div>Nights: <span id="nightsDisplay">0</span></div>
          </div>

          <button type="button" class="clear-dates">Clear Dates</button>

          <div class="subtotal">
            <span>Subtotal</span>
            <strong id="subtotalDisplay">Rs. 0</strong>
          </div>

          <button type="submit" class="book-now">BOOK NOW</button>
        </form>
      </aside>
    </div>

    
    <section class="property-info">
      <h1><?= htmlspecialchars($property['Name']) ?></h1>
      <p class="location"><?= htmlspecialchars($property['Location']) ?></p>
      <p class="price">
        $<?= number_format($property['Price'], 2) ?>
        &mdash; <?= htmlspecialchars($property['Capacity']) ?>
      </p>

      <div class="description">
        <h2>Description</h2>
        <p><?= nl2br(htmlspecialchars($property['Big_Description'])) ?></p>
      </div>

      <div class="amenities">
        <h2>Amenities</h2>
        <ul>
          <?php foreach (json_decode($property['Amenities'], true) ?: [] as $a): ?>
            <li><?= htmlspecialchars($a) ?></li>
          <?php endforeach; ?>
          <?php if (empty(json_decode($property['Amenities'], true))): ?>
            <li>No amenities listed.</li>
          <?php endif; ?>
        </ul>
      </div>
    </section>

    <section class="reviews" id="reviews">
      <h2>Guest Reviews</h2>
      <?php if (empty($reviews)): ?>
        <p>No reviews yet.</p>
      <?php else: ?>
        <?php foreach ($reviews as $r): ?>
          <div class="review-card">
            <p class="meta">
              <strong><?= htmlspecialchars($r['Username']) ?></strong>
              &mdash; <?= date('M j, Y', strtotime($r['Review_Date'])) ?>
            </p>
            <p><?= nl2br(htmlspecialchars($r['Comment'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
      <section class="leave-review">
        <h2>Leave a Review</h2>
        <?php if (empty($eligibleBookings)): ?>
          <p>You have no past stays to review.</p>
        <?php else: ?>
          <form action="SubmitReview.php" method="POST">
            <input type="hidden" name="property_id" value="<?= $property_id ?>">

            <?php if (count($eligibleBookings) > 1): ?>
              <label>
                Which stay?
                <select name="booking_id" required>
                  <?php foreach ($eligibleBookings as $b): ?>
                    <option value="<?= $b['Booking_ID'] ?>">
                      <?= date('M j, Y', strtotime($b['Check_In_Date'])) ?>
                      &ndash;
                      <?= date('M j, Y', strtotime($b['Check_Out_Date'])) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            <?php else: ?>
              <input
                type="hidden"
                name="booking_id"
                value="<?= $eligibleBookings[0]['Booking_ID'] ?>"
              >
              <p>
                Reviewing stay from
                <?= date('M j, Y', strtotime($eligibleBookings[0]['Check_In_Date'])) ?>
                &ndash;
                <?= date('M j, Y', strtotime($eligibleBookings[0]['Check_Out_Date'])) ?>
              </p>
            <?php endif; ?>

            <label>
              Your Review
              <textarea name="comment" rows="4" required></textarea>
            </label>
            <button type="submit">Submit Review</button>
          </form>
        <?php endif; ?>
      </section>
    <?php else: ?>
      <p><a href="#" id="logInBtn">Log in</a> to leave a review.</p>
    <?php endif; ?>

  </main>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const calEl = document.getElementById('calendar');
      if (!calEl || typeof flatpickr === 'undefined') return;

      const price        = parseFloat(calEl.dataset.price) || 0;
      const disabled     = JSON.parse(calEl.dataset.disabled || '[]');
      const ciInput      = document.getElementById('checkInInput');
      const coInput      = document.getElementById('checkOutInput');
      const ciDisplay    = document.getElementById('ciDisplay');
      const coDisplay    = document.getElementById('coDisplay');
      const nightsDisp   = document.getElementById('nightsDisplay');
      const subtotalDisp = document.getElementById('subtotalDisplay');
      const clearBtn     = document.querySelector('.clear-dates');

      const fp = flatpickr(calEl, {
        mode: 'range',
        inline: true,
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disable: disabled,    
        onChange: dates => {
          if (dates.length === 2) {
            const [start, end] = dates;
            const nights = Math.round((end - start)/(1000*60*60*24));
            ciInput.value = fp.formatDate(start, 'Y-m-d');
            coInput.value = fp.formatDate(end,   'Y-m-d');
            ciDisplay.textContent    = fp.formatDate(start, 'M j, Y');
            coDisplay.textContent    = fp.formatDate(end,   'M j, Y');
            nightsDisp.textContent   = nights;
            subtotalDisp.textContent = 'Rs. '+(nights*price).toLocaleString();
          }
        }
      });

      clearBtn.addEventListener('click', () => {
        fp.clear();
        ciInput.value = coInput.value = '';
        ciDisplay.textContent = coDisplay.textContent = '—';
        nightsDisp.textContent    = '0';
        subtotalDisp.textContent  = 'Rs. 0';
      });
    });
  </script>
</body>
</html>
