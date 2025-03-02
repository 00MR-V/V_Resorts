<?php
session_start();
require_once "../../../database/VResortsConnection.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: Unauthorized access";
    exit;
}

$admin_id = $_SESSION['user_id']; // Get the logged-in admin's ID

$propertyId = $_POST['propertyId'] ?? null;
$name = $_POST['propertyName'] ?? '';
$type = $_POST['propertyType'] ?? '';
$location = $_POST['propertyLocation'] ?? '';
$price = $_POST['propertyPrice'] ?? 0;
$availability = $_POST['propertyAvailability'] ?? 0;
$description = $_POST['propertyDescription'] ?? "No description available";
$big_description = $_POST['bigDescription'] ?? null;
$capacity = $_POST['propertyCapacity'] ?? null;

// Handle amenities - Convert from comma-separated string to JSON array
$amenities = isset($_POST['propertyAmenities']) ? json_encode(array_map('trim', explode(",", $_POST['propertyAmenities']))) : "[]";

// Ensure the uploads directory exists
$upload_dir = "../../uploads/properties/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle property photo upload
$photo_path = null;
if (!empty($_FILES['propertyPhoto']['name']) && $_FILES['propertyPhoto']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $file_type = mime_content_type($_FILES['propertyPhoto']['tmp_name']);

    if (in_array($file_type, $allowed_types)) {
        $photo_filename = time() . "_" . basename($_FILES['propertyPhoto']['name']);
        $photo_path = $upload_dir . $photo_filename;
        move_uploaded_file($_FILES['propertyPhoto']['tmp_name'], $photo_path);
    } else {
        echo "Error: Invalid image format for property photo.";
        exit;
    }
}

// Handle gallery photos (multiple images)
$gallery_photos = [];
if (!empty($_FILES['galleryPhotos']['tmp_name'][0])) {
    foreach ($_FILES['galleryPhotos']['tmp_name'] as $key => $tmp_name) {
        $file_type = mime_content_type($tmp_name);
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/webp'])) {
            $gallery_filename = time() . "_" . basename($_FILES['galleryPhotos']['name'][$key]);
            $gallery_path = $upload_dir . $gallery_filename;
            move_uploaded_file($tmp_name, $gallery_path);
            $gallery_photos[] = $gallery_path;
        }
    }
}
$gallery_photos_json = json_encode($gallery_photos);

try {
    if ($propertyId) {
        // Update Property
        $sql = "UPDATE property SET Name=?, Type=?, Location=?, Price=?, Availability=?, Description=?, Big_Description=?, propertyPhoto=?, Amenities=?, Gallery_Photos=?, Capacity=? WHERE Property_ID=? AND Admin_ID=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $type, $location, $price, $availability, $description, $big_description, $photo_path, $amenities, $gallery_photos_json, $capacity, $propertyId, $admin_id]);
        echo "Property updated successfully!";
    } else {
        // Insert New Property
        $sql = "INSERT INTO property (Admin_ID, Name, Type, Location, Price, Availability, Description, Big_Description, propertyPhoto, Amenities, Gallery_Photos, Capacity) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_id, $name, $type, $location, $price, $availability, $description, $big_description, $photo_path, $amenities, $gallery_photos_json, $capacity]);
        echo "New property added!";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
