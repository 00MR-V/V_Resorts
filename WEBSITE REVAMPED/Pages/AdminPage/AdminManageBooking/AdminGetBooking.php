<?php
session_start();
require_once "../../../database/VResortsConnection.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}
$admin_id = $_SESSION['user_id'];

// Check if a specific booking is requested (detailed view)
$bookingId = isset($_POST['bookingId']) ? trim($_POST['bookingId']) : null;
if ($bookingId) {
    $sql = "SELECT b.Booking_ID,
                   b.Check_In_Date,
                   b.Check_Out_Date,
                   b.Status,
                   p.Name AS propertyName,
                   CONCAT(c.FName, ' ', c.LName) AS customerName,
                   COALESCE(SUM(pm.Amount), 0) AS TotalPayment,
                   MAX(pm.Payment_Date) AS LastPaymentDate
            FROM booking b
            JOIN property p ON b.Property_ID = p.Property_ID
            JOIN customers c ON b.Customer_ID = c.Cust_Id
            LEFT JOIN payment pm ON b.Booking_ID = pm.Booking_ID
            WHERE b.Booking_ID = :bookingId
              AND p.Admin_ID = :admin_id
            GROUP BY b.Booking_ID, b.Check_In_Date, b.Check_Out_Date, b.Status, p.Name, c.FName, c.LName
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        echo json_encode(["error" => "Booking not found"]);
        exit;
    }

    $totalPayment = $booking['TotalPayment'] > 0 ? "$" . number_format($booking['TotalPayment']) : "N/A";
    $lastPaymentDate = $booking['LastPaymentDate'] ? $booking['LastPaymentDate'] : "N/A";
    $paymentString = "Total: " . $totalPayment . ", Last Payment: " . $lastPaymentDate;

    echo json_encode([
        "Booking_ID"    => $booking['Booking_ID'],
        "propertyName"  => $booking['propertyName'],
        "customerName"  => $booking['customerName'],
        "Check_In_Date" => $booking['Check_In_Date'],
        "Check_Out_Date"=> $booking['Check_Out_Date'],
        "Status"        => $booking['Status'],
        "Payment"       => $paymentString
    ]);
    exit;
}

// Otherwise, fetch all bookings for the admin's properties with optional filtering
$search = isset($_POST['search']) ? trim($_POST['search']) : "";
$sql = "SELECT b.Booking_ID,
               b.Check_In_Date,
               b.Check_Out_Date,
               b.Status,
               p.Name AS propertyName,
               CONCAT(c.FName, ' ', c.LName) AS customerName,
               COALESCE(SUM(pm.Amount), 0) AS TotalPayment,
               MAX(pm.Payment_Date) AS LastPaymentDate
        FROM booking b
        JOIN property p ON b.Property_ID = p.Property_ID
        JOIN customers c ON b.Customer_ID = c.Cust_Id
        LEFT JOIN payment pm ON b.Booking_ID = pm.Booking_ID
        WHERE p.Admin_ID = :admin_id";
if (!empty($search)) {
    $sql .= " AND (p.Name LIKE :search OR CONCAT(c.FName, ' ', c.LName) LIKE :search)";
}
$sql .= " GROUP BY b.Booking_ID, b.Check_In_Date, b.Check_Out_Date, b.Status, p.Name, c.FName, c.LName
          ORDER BY b.Check_In_Date DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
if (!empty($search)) {
    $searchParam = "%" . $search . "%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($bookings)) {
    echo "<tr><td colspan='8'>No bookings found.</td></tr>";
    exit;
}

$html = "";
foreach ($bookings as $b) {
    $totalPayment = $b['TotalPayment'] > 0 ? "$" . number_format($b['TotalPayment']) : "N/A";
    $lastPaymentDate = $b['LastPaymentDate'] ? htmlspecialchars($b['LastPaymentDate']) : "N/A";
    $paymentString = $totalPayment . " on " . $lastPaymentDate;

    $html .= "<tr>
        <td>" . htmlspecialchars($b['Booking_ID']) . "</td>
        <td>" . htmlspecialchars($b['propertyName']) . "</td>
        <td>" . htmlspecialchars($b['customerName']) . "</td>
        <td>" . htmlspecialchars($b['Check_In_Date']) . "</td>
        <td>" . htmlspecialchars($b['Check_Out_Date']) . "</td>
        <td>" . htmlspecialchars($b['Status']) . "</td>
        <td>" . $paymentString . "</td>
        <td>
            <button class='viewBookingBtn' data-id='" . $b['Booking_ID'] . "'>View Details</button>";
    
    // Display status update buttons based on current status
    switch ($b['Status']) {
        case 'Pending':
            $html .= " <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Confirmed'>Confirm</button>
                       <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Cancelled'>Cancel</button>";
            break;
        case 'Confirmed':
            $html .= " <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Completed'>Mark as Complete</button>
                       <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Cancelled'>Cancel</button>";
            break;
        case 'Cancelled':
            $html .= " <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Pending'>Reopen</button>";
            break;
        case 'Completed':
            // Optionally, you can allow reopening a completed booking
            // $html .= " <button class='updateStatusBtn' data-id='" . $b['Booking_ID'] . "' data-newstatus='Pending'>Reopen</button>";
            break;
    }
    
    $html .= "</td></tr>";
}
echo $html;
exit;
?>
