<?php


session_start();
require_once '../../database/VResortsConnection.php';


if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = 'Please log in before booking.';
    header('Location: LoginPage.php');
    exit;
}

$customerId = (int)$_SESSION['user_id'];
$propertyId = isset($_POST['property_id']) ? (int)$_POST['property_id'] : 0;
$checkIn    = $_POST['check_in']  ?? '';
$checkOut   = $_POST['check_out'] ?? '';


if (!$propertyId || !$checkIn || !$checkOut) {
    $_SESSION['error_msg'] = 'Please select both check‑in and check‑out dates.';
    header("Location: SpecificPropertyPage.php?property_id={$propertyId}");
    exit;
}
if ($checkIn >= $checkOut) {
    $_SESSION['error_msg'] = 'Your check‑out must be after check‑in.';
    header("Location: SpecificPropertyPage.php?property_id={$propertyId}");
    exit;
}

try {
    
    $avail = $pdo->prepare("
        SELECT COUNT(*) FROM booking
        WHERE Property_ID = :pid
          AND Status != 'Cancelled'
          AND NOT (
            Check_Out_Date <= :ci
            OR Check_In_Date >= :co
          )
    ");
    $avail->execute([
        ':pid' => $propertyId,
        ':ci'  => $checkIn,
        ':co'  => $checkOut,
    ]);
    if ((int)$avail->fetchColumn() > 0) {
        $_SESSION['error_msg'] = 'Sorry, those dates are no longer available.';
        header("Location: SpecificPropertyPage.php?property_id={$propertyId}");
        exit;
    }

    
    $ins = $pdo->prepare("
        INSERT INTO booking
          (Property_ID, Customer_ID, Check_In_Date, Check_Out_Date, Status)
        VALUES
          (:pid, :cid, :ci, :co, 'Pending')
    ");
    $ins->execute([
        ':pid' => $propertyId,
        ':cid' => $customerId,
        ':ci'  => $checkIn,
        ':co'  => $checkOut
    ]);
    $bookingId = $pdo->lastInsertId();

    
    header("Location: PaymentPage.php?booking_id={$bookingId}");
    exit;

} catch (PDOException $e) {
    error_log("Booking error: " . $e->getMessage());
    $_SESSION['error_msg'] = 'An unexpected error occurred. Please try again.';
    header("Location: SpecificPropertyPage.php?property_id={$propertyId}");
    exit;
}
