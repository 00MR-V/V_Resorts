<?php
session_start();
require_once "../../../database/VResortsConnection.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: Unauthorized access";
    exit;
}

$admin_id = $_SESSION['user_id']; // Get the logged-in admin's ID

$propertyId      = $_POST['propertyId'] ?? null;
$name            = $_POST['propertyName'] ?? '';
$type            = $_POST['propertyType'] ?? '';
$location        = $_POST['propertyLocation'] ?? '';
$price           = $_POST['propertyPrice'] ?? 0;
$availability    = $_POST['propertyAvailability'] ?? 0;
$description     = $_POST['propertyDescription'] ?? "No description available";
$big_description = $_POST['bigDescription'] ?? null;
$capacity        = $_POST['propertyCapacity'] ?? null;

// Handle amenities - Convert from comma-separated string to JSON array
$amenities = isset($_POST['propertyAmenities']) ? json_encode(array_map('trim', explode(",", $_POST['propertyAmenities']))) : "[]";

// Process property photo upload and store binary data directly in the database
$property_photo_blob = null;
if (!empty($_FILES['propertyPhoto']['name']) && $_FILES['propertyPhoto']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $file_type = mime_content_type($_FILES['propertyPhoto']['tmp_name']);
    if (in_array($file_type, $allowed_types)) {
        $property_photo_blob = file_get_contents($_FILES['propertyPhoto']['tmp_name']);
    } else {
        echo "Error: Invalid image format for property photo.";
        exit;
    }
}

// Process gallery photos upload: read each file and store as a base64 encoded string in a JSON array
$gallery_photos_array = [];
if (!empty($_FILES['galleryPhotos']['tmp_name'][0])) {
    foreach ($_FILES['galleryPhotos']['tmp_name'] as $key => $tmp_name) {
        $file_type = mime_content_type($tmp_name);
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/webp'])) {
            $gallery_blob = file_get_contents($tmp_name);
            // Store each gallery image as a base64 encoded string
            $gallery_photos_array[] = base64_encode($gallery_blob);
        }
    }
}
$gallery_photos_json = json_encode($gallery_photos_array);

try {
    if ($propertyId) {
        // Update Property using COALESCE to keep existing images if new ones are not provided.
        // For gallery photos, if no new images are uploaded, we use NULLIF to treat an empty JSON array ('[]') as NULL.
        $sql = "UPDATE property SET 
                    Name = ?,
                    Type = ?,
                    Location = ?,
                    Price = ?,
                    Availability = ?,
                    Description = ?,
                    Big_Description = ?,
                    propertyPhoto = COALESCE(?, propertyPhoto),
                    Amenities = ?,
                    Gallery_Photos = COALESCE(NULLIF(?, '[]'), Gallery_Photos),
                    Capacity = ?
                WHERE Property_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name,
            $type,
            $location,
            $price,
            $availability,
            $description,
            $big_description,
            $property_photo_blob, // if null, propertyPhoto remains unchanged
            $amenities,
            $gallery_photos_json, // if '[]', Gallery_Photos remains unchanged
            $capacity,
            $propertyId
        ]);
        echo "Property updated successfully!";
    } else {
        // Insert New Property
        $sql = "INSERT INTO property (Admin_ID, Name, Type, Location, Price, Availability, Description, Big_Description, propertyPhoto, Amenities, Gallery_Photos, Capacity) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $admin_id,
            $name,
            $type,
            $location,
            $price,
            $availability,
            $description,
            $big_description,
            $property_photo_blob,
            $amenities,
            $gallery_photos_json,
            $capacity
        ]);
        echo "New property added!";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
