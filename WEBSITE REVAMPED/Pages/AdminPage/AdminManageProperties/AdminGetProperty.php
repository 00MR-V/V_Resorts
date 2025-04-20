<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


$propertyId = isset($_POST['propertyId']) ? trim($_POST['propertyId']) : null;
if ($propertyId !== null && $propertyId !== '') {
    header('Content-Type: application/json; charset=UTF-8');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}


if (!isset($_SESSION['user_id'])) {
    if ($propertyId) {
        echo json_encode(['error' => 'Unauthorized access']);
    } else {
        echo '<tr><td colspan="13">Error: Unauthorized access &mdash; please log in.</td></tr>';
    }
    exit;
}
$adminId = (int)$_SESSION['user_id'];


require_once "../../../database/VResortsConnection.php";


if ($propertyId) {
    try {
        $sql = "
            SELECT
                Property_ID, Name, Type, Location,
                Price, Description, Big_Description,
                Amenities, Capacity, Availability,
                propertyPhoto, Gallery_Photos
            FROM property
            WHERE Property_ID = :pid
              AND Admin_ID    = :aid
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pid' => (int)$propertyId,
            ':aid' => $adminId
        ]);
        $prop = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prop) {
            echo json_encode(['error' => 'Property not found or permission denied']);
            exit;
        }

        
        $prop['Amenities'] = !empty($prop['Amenities'])
            ? json_decode($prop['Amenities'], true)
            : [];

       
        $prop['propertyPhoto'] = null;
        if (!empty($prop['propertyPhoto'])) {
            $prop['propertyPhoto'] = base64_encode($prop['propertyPhoto']);
        }

        
        $prop['Gallery_Photos'] = !empty($prop['Gallery_Photos'])
            ? json_decode($prop['Gallery_Photos'], true)
            : [];

        echo json_encode($prop);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
    }
    exit;
}


try {
    $sql = "
        SELECT
            Property_ID, Name, Type, Location,
            Price, Description, Big_Description,
            Amenities, Capacity, Availability
        FROM property
        WHERE Admin_ID = :aid
        ORDER BY Property_ID DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':aid' => $adminId]);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($properties)) {
        echo '<tr><td colspan="13">No properties found.</td></tr>';
        exit;
    }

    foreach ($properties as $row) {
        $availability   = $row['Availability'] ? 'Unavailable' : 'Available';
        $descPreview    = htmlspecialchars(substr($row['Description'], 0, 50)) . '...';
        $bigDescPreview = htmlspecialchars(substr($row['Big_Description'], 0, 50)) . '...';
        $amenitiesList  = '';
        $amenArr        = json_decode($row['Amenities'], true);
        if (is_array($amenArr) && !empty($amenArr)) {
            $amenitiesList = htmlspecialchars(implode(', ', $amenArr));
        }

        echo "
        <tr>
            <td>{$row['Property_ID']}</td>
            <td>" . htmlspecialchars($row['Name']) . "</td>
            <td>" . htmlspecialchars($row['Type']) . "</td>
            <td>" . htmlspecialchars($row['Location']) . "</td>
            <td>\${$row['Price']}</td>
            <td>{$availability}</td>
            <td>{$descPreview}</td>
            <td>{$bigDescPreview}</td>
            <td>" . htmlspecialchars($row['Capacity']) . "</td>
            <td>{$amenitiesList}</td>
            <td>
                <button class=\"editBtn\" data-id=\"{$row['Property_ID']}\">Edit</button>
                <button class=\"deleteBtn\" data-id=\"{$row['Property_ID']}\">Delete</button>
            </td>
        </tr>
        ";
    }
} catch (PDOException $e) {
    echo '<tr><td colspan="13">DB Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
