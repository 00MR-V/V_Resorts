<?php
session_start();
require_once "../../../database/VResortsConnection.php";


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

$admin_id = $_SESSION['user_id'];
$property_id = isset($_POST['property_id']) ? trim($_POST['property_id']) : "";


if ($property_id === "" || strtolower($property_id) === "all") {
    $sql = "SELECT COUNT(DISTINCT b.Booking_ID) AS totalBookings,
                   COALESCE(SUM(bp.totalPayment), 0) AS totalRevenue,
                   COALESCE(AVG(bp.totalPayment), 0) AS avgBookingValue,
                   SUM(CASE WHEN b.Status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelledBookings
            FROM booking b
            LEFT JOIN (
                SELECT Booking_ID, SUM(Amount) AS totalPayment
                FROM payment
                GROUP BY Booking_ID
            ) bp ON b.Booking_ID = bp.Booking_ID
            WHERE b.Property_ID IN (SELECT Property_ID FROM property WHERE Admin_ID = :admin_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    
    $sql = "SELECT Property_ID FROM property WHERE Property_ID = :property_id AND Admin_ID = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(["error" => "Unauthorized property"]);
        exit;
    }
   
    $sql = "SELECT COUNT(DISTINCT b.Booking_ID) AS totalBookings,
                   COALESCE(SUM(bp.totalPayment), 0) AS totalRevenue,
                   COALESCE(AVG(bp.totalPayment), 0) AS avgBookingValue,
                   SUM(CASE WHEN b.Status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelledBookings
            FROM booking b
            LEFT JOIN (
                SELECT Booking_ID, SUM(Amount) AS totalPayment
                FROM payment
                GROUP BY Booking_ID
            ) bp ON b.Booking_ID = bp.Booking_ID
            WHERE b.Property_ID = :property_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

$totalBookings    = (int)$result['totalBookings'];
$totalRevenue     = (float)$result['totalRevenue'];
$avgBookingValue  = (float)$result['avgBookingValue'];
$cancelledBookings= (int)$result['cancelledBookings'];
$cancellationRate = $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;

echo json_encode([
    "totalBookings"   => $totalBookings,
    "totalRevenue"    => $totalRevenue,
    "avgBookingValue" => $avgBookingValue,
    "cancelledBookings" => $cancelledBookings,
    "cancellationRate"=> $cancellationRate
]);
exit;
?>
