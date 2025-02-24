<?php
require_once '../../database/VResortsConnction.php';
$show_search_box = false; // Hide search box
$property_id = isset($_GET['property_id']) ? (int)$_GET['property_id'] : null;

if (!$property_id) {
    die("Property not found!");
}

// Fetch property details
$query = $pdo->prepare("SELECT * FROM property WHERE Property_ID = :property_id");
$query->bindParam(':property_id', $property_id, PDO::PARAM_INT);
$query->execute();
$property = $query->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    die("Property not found!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['Name']); ?> - V Resorts</title>
    <link rel="stylesheet" href="SpecificPropertyPage.css">
    <script src="SpecificPropertyPage.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/HeaderComponents/HeaderComponent.css">
    <script src="../../components/HeaderComponents/HeaderComponent.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/LogInModal/LogInModal.css">
    <script src="../../components/LogInModal/LogInModal.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/SignUpModal.css">
    <script src="../../components/SignUpModal/SignUpModal.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/AdminSignUpModal.css">
    <script src="../../components/SignUpModal/AdminSignUpModal.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/PropertiesComponents/Properties.css">

    <script src="../../components/LogOutComponent/LogOutComponent.js"></script>

    <link rel="stylesheet" type="text/css" href="../../components/FooterComponents/FooterComponent.css">
</head>

<body>
    <?php include '../../components/HeaderComponents/HeaderComponent.php'; ?>

    <main class="property-details">
        <!-- Gallery Section -->
        <div class="gallery">
        <?php
        if (!empty($property['propertyPhoto'])) {
            // If propertyPhoto is stored as a binary blob (e.g., in BLOB format in the database)
            echo "<img src='data:image/jpeg;base64," . base64_encode($property['propertyPhoto']) . "' alt='Property Photo'>";
        } else {
            echo "<p>No photos available for this property.</p>";
        }
        ?>
        </div>

        <div class="gallery">
            <?php
            $galleryPhotos = json_decode($property['Gallery_Photos'], true);
            if (is_array($galleryPhotos) && !empty($galleryPhotos)) {
                foreach ($galleryPhotos as $photo) {
                    echo "<img src='../../images/properties/$photo' alt='Gallery Photo'>";
                }
            } else {
                echo "<p>No gallery photos available for this property.</p>";
            }
            ?>
        </div>

        <!-- Property Info Section -->
        <section class="property-info">
            <h1><?php echo htmlspecialchars($property['Name']); ?></h1>
            <p class="location"><?php echo htmlspecialchars($property['Location']); ?></p>
            <p class="price">Price: $<?php echo number_format($property['Price'], 2); ?> per night</p>
            <p class="capacity"><?php echo htmlspecialchars($property['Capacity']); ?></p>

            <div class="description">
                <h2>Description</h2>
                <p><?php echo htmlspecialchars($property['Big_Description']); ?></p>
            </div>

            <div class="amenities">
                <h2>Amenities</h2>
                <ul>
                    <?php
                    $amenities = json_decode($property['Amenities'], true);
                    if (is_array($amenities) && !empty($amenities)) {
                        foreach ($amenities as $amenity) {
                            echo "<li>" . htmlspecialchars($amenity) . "</li>";
                        }
                    } else {
                        echo "<p>No amenities listed for this property.</p>";
                    }
                    ?>
                </ul>
            </div>
        </section>

        <!-- Booking Section -->
        <aside class="booking-section">
            <h2>Book This Property</h2>
            <form action="BookProperty.php" method="POST">
                <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
                <label for="check_in">Check-In</label>
                <input type="date" name="check_in" id="check_in" required>
                <label for="check_out">Check-Out</label>
                <input type="date" name="check_out" id="check_out" required>
                <button type="submit" class="book-now-btn">Book Now</button>
            </form>
        </aside>
    </main>


    <?php include '../../components/FooterComponents/FooterComponent.php'; ?>


    <?php include '../../components/LogInModal/LogInModal.php'; ?>
    <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
    <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>

</html>