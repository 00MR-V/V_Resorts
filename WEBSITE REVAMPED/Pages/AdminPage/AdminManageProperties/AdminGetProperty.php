<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../database/VResortsConnection.php"; // Ensure this path is correct

header("Content-Type: application/json");

// Debug: Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access - Admin not logged in"]);
    exit;
}

// Debug: Check if propertyId is provided
if (!isset($_POST['propertyId']) || empty($_POST['propertyId'])) {
    echo json_encode(["error" => "No property ID provided"]);
    exit;
}

$admin_id = $_SESSION['user_id'];
$propertyId = $_POST['propertyId'];

try {
    // Debug: Check if database connection is working
    if (!$pdo) {
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    // Fetch property details only if it belongs to the logged-in admin
    $query = "SELECT * FROM property WHERE Property_ID = :propertyId AND Admin_ID = :adminId LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
    $stmt->bindParam(':adminId', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Check if query returned any result
    if (!$property) {
        echo json_encode(["error" => "Property not found or access denied"]);
        exit;
    }

    // Convert JSON fields back to readable format
    $property['Amenities'] = json_decode($property['Amenities'], true);

    // Debug: Check if final output is correct
    echo json_encode($property);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
