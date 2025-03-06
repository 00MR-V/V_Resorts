<?php
session_start();
require_once "../../../database/VResortsConnection.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

$admin_id = $_SESSION['user_id'];
$property_id = isset($_POST['property_id']) ? trim($_POST['property_id']) : "";
$groupBy = isset($_POST['groupBy']) ? trim($_POST['groupBy']) : "month";

$format = ($groupBy === "year") ? "%Y" : "%Y-%m";

if ($property_id === "" || strtolower($property_id) === "all") {
    $sql = "SELECT DATE_FORMAT(pm.Payment_Date, '$format') AS period, SUM(pm.Amount) AS revenue
            FROM payment pm
            JOIN booking b ON pm.Booking_ID = b.Booking_ID
            WHERE b.Property_ID IN (SELECT Property_ID FROM property WHERE Admin_ID = :admin_id)
            GROUP BY period
            ORDER BY period ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Verify the property belongs to the logged-in admin
    $sql = "SELECT Property_ID FROM property WHERE Property_ID = :property_id AND Admin_ID = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(["error" => "Unauthorized property"]);
        exit;
    }
    $sql = "SELECT DATE_FORMAT(pm.Payment_Date, '$format') AS period, SUM(pm.Amount) AS revenue
            FROM payment pm
            JOIN booking b ON pm.Booking_ID = b.Booking_ID
            WHERE b.Property_ID = :property_id
            GROUP BY period
            ORDER BY period ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($results);
exit;
?>
