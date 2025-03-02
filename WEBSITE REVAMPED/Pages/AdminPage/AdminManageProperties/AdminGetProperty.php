<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Determine response type based on whether a propertyId is provided.
$propertyId = isset($_POST['propertyId']) ? trim($_POST['propertyId']) : null;
if ($propertyId) {
    header("Content-Type: application/json; charset=UTF-8");
} else {
    header("Content-Type: text/html; charset=UTF-8");
}

// Ensure admin is logged in.
if (!isset($_SESSION['user_id'])) {
    if ($propertyId) {
        echo json_encode(["error" => "Unauthorized access"]);
    } else {
        echo "<tr><td colspan='8'>Error: Unauthorized access - Admin not logged in</td></tr>";
    }
    exit;
}
$admin_id = $_SESSION['user_id'];

// -------------------[ Database Connection ]-------------------
$dsn    = "mysql:host=localhost;dbname=v_resorts;charset=utf8";
$dbUser = "root";
$dbPass = "";
$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $ex) {
    if ($propertyId) {
        echo json_encode(["error" => "DB Connection Error: " . $ex->getMessage()]);
    } else {
        echo "<tr><td colspan='8'>DB Connection Error: " . htmlspecialchars($ex->getMessage()) . "</td></tr>";
    }
    exit;
}

// -------------------[ Single-Property Fetch (JSON response) ]-------------------
if ($propertyId) {
    try {
        // Removed Admin_ID check so older properties can be edited.
        $sql = "SELECT * FROM property WHERE Property_ID = :propertyId LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
        $stmt->execute();
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property) {
            echo json_encode(["error" => "Property not found"]);
            exit;
        }

        // Decode amenities from JSON.
        $property['Amenities'] = !empty($property['Amenities'])
            ? json_decode($property['Amenities'], true)
            : [];

        echo json_encode($property);
        exit;
    } catch (PDOException $e) {
        echo json_encode(["error" => "DB Error: " . $e->getMessage()]);
        exit;
    }
}

// -------------------[ Multi-Property Fetch (HTML table) ]-------------------
try {
    // Removed Admin_ID check so all properties are shown.
    $sql = "SELECT * FROM property";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($properties)) {
        echo "<tr><td colspan='8'>No properties found.</td></tr>";
        exit;
    }

    $html = "";
    foreach ($properties as $row) {
        $description  = htmlspecialchars(substr($row['Description'], 0, 50)) . "...";
        $availability = $row['Availability'] ? 'Unavailable' : 'Available';

        $html .= "<tr>
                    <td>{$row['Property_ID']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['Type']}</td>
                    <td>{$row['Location']}</td>
                    <td>\${$row['Price']}</td>
                    <td>{$availability}</td>
                    <td>{$description}</td>
                    <td>
                        <button class='editBtn' data-id='{$row['Property_ID']}'>Edit</button>
                        <button class='deleteBtn' data-id='{$row['Property_ID']}'>Delete</button>
                    </td>
                  </tr>";
    }
    echo $html;
    exit;
} catch (PDOException $e) {
    echo "<tr><td colspan='8'>DB Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
    exit;
}
