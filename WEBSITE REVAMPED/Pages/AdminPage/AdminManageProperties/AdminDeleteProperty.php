<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../database/VResortsConnection.php";


if (!isset($_SESSION['user_id'])) {
    echo "Error: Unauthorized access";
    exit;
}
$adminId    = (int)$_SESSION['user_id'];
$propertyId = isset($_POST['propertyId']) ? (int)$_POST['propertyId'] : 0;

try {
  
    $sql  = "DELETE FROM property 
             WHERE Property_ID = :pid
               AND Admin_ID    = :aid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':pid' => $propertyId,
        ':aid' => $adminId
    ]);

    if ($stmt->rowCount()) {
        echo "Property deleted successfully!";
    } else {
        echo "Error: Could not delete (not found or permission denied).";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
