<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../database/VResortsConnection.php";


if (!isset($_SESSION['user_id'])) {
    echo "Error: Unauthorized access";
    exit;
}
$adminId = (int)$_SESSION['user_id'];


$propertyId      = isset($_POST['propertyId']) && $_POST['propertyId'] !== '' 
                   ? (int)$_POST['propertyId'] 
                   : null;
$name            = trim($_POST['propertyName']      ?? '');
$type            = trim($_POST['propertyType']      ?? '');
$location        = trim($_POST['propertyLocation']  ?? '');
$price           = (float)($_POST['propertyPrice']  ?? 0);
$availability    = (int)($_POST['propertyAvailability'] ?? 0);
$description     = trim($_POST['propertyDescription'] ?? '');
$bigDescription  = trim($_POST['bigDescription']      ?? '');
$capacity        = trim($_POST['propertyCapacity']   ?? '');


$amenitiesArr = [];
if (!empty($_POST['propertyAmenities'])) {
    $amenitiesArr = array_map('trim', explode(',', $_POST['propertyAmenities']));
}
$amenitiesJson = json_encode($amenitiesArr, JSON_UNESCAPED_UNICODE);


$propertyPhotoBlob = null;
if (!empty($_FILES['propertyPhoto']['tmp_name']) && $_FILES['propertyPhoto']['error'] === UPLOAD_ERR_OK) {
    $mime = mime_content_type($_FILES['propertyPhoto']['tmp_name']);
    if (in_array($mime, ['image/jpeg','image/png','image/webp'], true)) {
        $propertyPhotoBlob = file_get_contents($_FILES['propertyPhoto']['tmp_name']);
    } else {
        echo "Error: Invalid property image format.";
        exit;
    }
}


$galleryArr = [];
if (!empty($_FILES['galleryPhotos']['tmp_name'][0])) {
    foreach ($_FILES['galleryPhotos']['tmp_name'] as $tmp) {
        if (is_uploaded_file($tmp)) {
            $mime = mime_content_type($tmp);
            if (in_array($mime, ['image/jpeg','image/png','image/webp'], true)) {
                $galleryArr[] = base64_encode(file_get_contents($tmp));
            }
        }
    }
}
$galleryJson = json_encode($galleryArr, JSON_UNESCAPED_UNICODE);

try {
    if ($propertyId) {

        $sql = "
            UPDATE property SET
                Name            = ?,
                Type            = ?,
                Location        = ?,
                Price           = ?,
                Availability    = ?,
                Description     = ?,
                Big_Description = ?,
                propertyPhoto   = COALESCE(?, propertyPhoto),
                Amenities       = ?,
                Gallery_Photos  = COALESCE(NULLIF(?, '[]'), Gallery_Photos),
                Capacity        = ?
            WHERE Property_ID = ?
              AND Admin_ID    = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name,
            $type,
            $location,
            $price,
            $availability,
            $description,
            $bigDescription,
            $propertyPhotoBlob,
            $amenitiesJson,
            $galleryJson,
            $capacity,
            $propertyId,
            $adminId
        ]);
        echo "Property updated successfully!";
    } else {
   
        $sql = "
            INSERT INTO property
              (Admin_ID, Name, Type, Location, Price, Availability,
               Description, Big_Description, propertyPhoto,
               Amenities, Gallery_Photos, Capacity)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $adminId,
            $name,
            $type,
            $location,
            $price,
            $availability,
            $description,
            $bigDescription,
            $propertyPhotoBlob,
            $amenitiesJson,
            $galleryJson,
            $capacity
        ]);
        echo "New property added!";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
