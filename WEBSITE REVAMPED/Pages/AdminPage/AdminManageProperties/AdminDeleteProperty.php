<?php
require_once "../../../database/VResortsConnection.php";

$propertyId = $_POST['propertyId'];

$sql = "DELETE FROM property WHERE Property_ID = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$propertyId])) {
    echo "Property deleted successfully!";
} else {
    echo "❌ Error deleting property.";
}

