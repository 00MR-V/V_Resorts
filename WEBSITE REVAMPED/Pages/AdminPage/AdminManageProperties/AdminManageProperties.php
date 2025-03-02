<?php
session_start();
require_once "../../../database/VResortsConnection.php"; // Database connection file

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../AdminPage.php"); // Redirect if not logged in
    exit;
}

$admin_id = $_SESSION['user_id']; // Get logged-in admin's ID

// Fetch properties owned by the logged-in admin
$query = "SELECT * FROM property WHERE Admin_ID = :admin_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties</title>

    <!-- Main CSS for this page -->
    <link rel="stylesheet" href="../AdminManageProperties/AdminManageProperties.css">

    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Main JS for this page -->
    <script src="../AdminManageProperties/AdminManageProperties.js"></script>

    <!-- Include Sidebar CSS -->
    <link rel="stylesheet" href="../AdminSideBar.css">
</head>

<body>

    <!-- Include Sidebar -->
    <?php include "../AdminSideBar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Your Properties</h1>

        <!-- Add Property Button -->
        <button id="addPropertyBtn">Add New Property</button>

        <!-- Properties Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Description</th>
                    <th>Big Description</th>
                    <th>Capacity</th>
                    <th>Amenities</th>
                    <th>Property Image</th>
                    <th>Gallery</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $row) { ?>
                    <tr>
                        <td><?php echo $row['Property_ID']; ?></td>
                        <td><?php echo $row['Name']; ?></td>
                        <td><?php echo $row['Type']; ?></td>
                        <td><?php echo $row['Location']; ?></td>
                        <td>$<?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Availability'] ? 'Unavailable' : 'Available'; ?></td>
                        <td>
                            <?php
                            $desc_words = explode(" ", $row['Description']);
                            echo implode(" ", array_slice($desc_words, 0, 10));
                            if (count($desc_words) > 10) {
                                echo " ... <a href='#' class='read-more' data-fulltext='" . htmlspecialchars($row['Description']) . "'>Read More</a>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $big_desc_words = explode(" ", $row['Big_Description']);
                            echo implode(" ", array_slice($big_desc_words, 0, 10));
                            if (count($big_desc_words) > 10) {
                                echo " ... <a href='#' class='read-more' data-fulltext='" . htmlspecialchars($row['Big_Description']) . "'>Read More</a>";
                            }
                            ?>
                        </td>
                        <td><?php echo $row['Capacity']; ?></td>
                        <!-- Decode JSON for Amenities -->
                        <td>
                            <?php
                            $amenities = json_decode($row['Amenities'], true);
                            echo is_array($amenities) ? implode(", ", $amenities) : "No Amenities Listed";
                            ?>
                        </td>
                        <!-- Display Property Image from BLOB -->
                        <td>
                            <?php
                            if (!empty($row['propertyPhoto'])) {
                                $base64Image = base64_encode($row['propertyPhoto']);
                                echo "<img src='data:image/jpeg;base64,$base64Image' width='100' height='100'>";
                            } else {
                                echo "No Image";
                            }
                            ?>
                        </td>
                        <!-- Display Gallery Images from BLOB -->
                        <td>
                            <?php
                            $gallery = json_decode($row['Gallery_Photos'], true);
                            if (!empty($gallery) && is_array($gallery)) {
                                foreach ($gallery as $photo) {
                                    $base64Gallery = base64_encode($photo);
                                    echo "<img src='data:image/jpeg;base64,$base64Gallery' width='50' height='50' style='margin: 2px;'>";
                                }
                            } else {
                                echo "No Gallery";
                            }
                            ?>
                        </td>
                        <td>
                            <button class="editBtn" data-id="<?php echo $row['Property_ID']; ?>">Edit</button>
                            <button class="deleteBtn" data-id="<?php echo $row['Property_ID']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Add/Edit Property Modal -->
        <div id="propertyModal" class="modal hidden">
            <div class="modal-content">
                <!-- Close Button (X) -->
                <span class="close-button" id="closeModal">&times;</span>

                <!-- Modal Title -->
                <h2 id="modalTitle">Add New Property</h2>

                <!-- Form -->
                <form id="propertyForm" enctype="multipart/form-data">
                    <!-- Hidden ID Field (For Edit) -->
                    <input type="hidden" id="propertyId" name="propertyId">

                    <div class="form-group">
                        <label for="propertyName">Name:</label>
                        <input type="text" id="propertyName" name="propertyName" required>
                    </div>

                    <div class="form-group">
                        <label for="propertyType">Type:</label>
                        <input type="text" id="propertyType" name="propertyType" required>
                    </div>

                    <div class="form-group">
                        <label for="propertyLocation">Location:</label>
                        <input type="text" id="propertyLocation" name="propertyLocation" required>
                    </div>

                    <div class="form-group">
                        <label for="propertyPrice">Price:</label>
                        <input type="number" id="propertyPrice" name="propertyPrice" required>
                    </div>

                    <div class="form-group">
                        <label for="propertyDescription">Description:</label>
                        <textarea id="propertyDescription" name="propertyDescription" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="bigDescription">Big Description:</label>
                        <textarea id="bigDescription" name="bigDescription"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="propertyCapacity">Capacity:</label>
                        <input type="text" id="propertyCapacity" name="propertyCapacity">
                    </div>

                    <div class="form-group">
                        <label for="propertyAmenities">Amenities (Comma-separated):</label>
                        <input type="text" id="propertyAmenities" name="propertyAmenities">
                    </div>

                    <div class="form-group">
                        <label for="propertyAvailability">Availability:</label>
                        <select id="propertyAvailability" name="propertyAvailability">
                            <option value="0">Available</option>
                            <option value="1">Unavailable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="propertyPhoto">Property Image:</label>
                        <input type="file" id="propertyPhoto" name="propertyPhoto">
                    </div>

                    <div class="form-group">
                        <label for="galleryPhotos">Gallery Photos (Multiple):</label>
                        <input type="file" id="galleryPhotos" name="galleryPhotos[]" multiple>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="save-button">Save</button>
                        <button type="button" class="cancel-button" id="closeModal2">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div> <!-- End of Main Content -->

    <!-- Include Sidebar JS -->
    <script src="../AdminSideBar.js"></script>
</body>

</html>