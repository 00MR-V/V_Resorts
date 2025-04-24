<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../database/VResortsConnection.php';

use Opis\JsonSchema\Validator;

header('Content-Type: application/json; charset=utf-8');

if (! isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$adminId   = (int)$_SESSION['user_id'];
$bookingId = trim((string)($_POST['bookingId'] ?? ''));

if ($bookingId !== '') {
    $stmt = $pdo->prepare(<<<'SQL'
        SELECT
            b.Booking_ID,
            b.Check_In_Date,
            b.Check_Out_Date,
            b.Status,
            p.Name         AS propertyName,
            CONCAT(c.FName, " ", c.LName) AS customerName,
            COALESCE(SUM(pm.Amount), 0)   AS totalPayment,
            MAX(pm.Payment_Date)          AS lastPaymentDate
        FROM booking b
        JOIN property p ON b.Property_ID = p.Property_ID
        JOIN customers c ON b.Customer_ID = c.Cust_Id
        LEFT JOIN payment pm ON b.Booking_ID = pm.Booking_ID
        WHERE b.Booking_ID = :bid
          AND p.Admin_ID   = :aid
        GROUP BY b.Booking_ID
        LIMIT 1
    SQL);
    $stmt->execute([':bid' => $bookingId, ':aid' => $adminId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $row) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    $total = ((int)$row['totalPayment'] > 0)
        ? '$' . number_format((int)$row['totalPayment'])
        : 'N/A';
    $last  = $row['lastPaymentDate'] ?? 'N/A';

    $response = [
        'Booking_ID'     => (int)$row['Booking_ID'],
        'propertyName'   => $row['propertyName'],
        'customerName'   => $row['customerName'],
        'Check_In_Date'  => $row['Check_In_Date'],
        'Check_Out_Date' => $row['Check_Out_Date'],
        'Status'         => $row['Status'],
        'Payment'        => "Total: {$total}, Last Payment: {$last}",
    ];

    // load schema as stdClass
    $schemaJson = @file_get_contents(__DIR__ . '/../../../schemas/booking.schema.json');
    if ($schemaJson === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Cannot read schema file']);
        exit;
    }
    $schemaData = json_decode($schemaJson);
    if ($schemaData === null) {
        http_response_code(500);
        echo json_encode(['error' => 'Invalid JSON in schema']);
        exit;
    }

    // validate
    $validator = new Validator();
    // convert response into stdClass for validation
    $toValidate = json_decode(json_encode($response));
    $result     = $validator->validate($toValidate, $schemaData);

    if (! $result->isValid()) {
        http_response_code(500);
        echo json_encode(['error' => 'Schema validation failed']);
        exit;
    }

    // optionally persist
    @file_put_contents(
        __DIR__ . '/../../../data/last_booking.json',
        json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// --- no bookingId: return the table rows ---
$search = trim((string)($_POST['search'] ?? ''));
$sql    = <<<'SQL'
    SELECT
      b.Booking_ID,
      b.Check_In_Date,
      b.Check_Out_Date,
      b.Status,
      p.Name    AS propertyName,
      CONCAT(c.FName," ",c.LName) AS customerName,
      COALESCE(SUM(pm.Amount),0)   AS totalPayment,
      MAX(pm.Payment_Date)         AS lastPaymentDate
    FROM booking b
    JOIN property p ON b.Property_ID = p.Property_ID
    JOIN customers c ON b.Customer_ID = c.Cust_Id
    LEFT JOIN payment pm ON b.Booking_ID = pm.Booking_ID
    WHERE p.Admin_ID = :aid
SQL;
$params = [':aid' => $adminId];

if ($search !== '') {
    $sql .= ' AND (p.Name LIKE :search OR CONCAT(c.FName," ",c.LName) LIKE :search)';
    $params[':search'] = "%{$search}%";
}
$sql .= ' GROUP BY b.Booking_ID ORDER BY b.Check_In_Date DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (! $rows) {
    echo '<tr><td colspan="8">No bookings found.</td></tr>';
    exit;
}

$html = '';
foreach ($rows as $b) {
    $total = ((int)$b['totalPayment'] > 0)
        ? '$' . number_format((int)$b['totalPayment'])
        : 'N/A';
    $last = $b['lastPaymentDate'] ?? 'N/A';
    $pay  = htmlspecialchars("{$total} on {$last}", ENT_QUOTES);

    $html .= '<tr>'
           . '<td>' . htmlspecialchars((string)$b['Booking_ID'],ENT_QUOTES) . '</td>'
           . '<td>' . htmlspecialchars($b['propertyName'],ENT_QUOTES) . '</td>'
           . '<td>' . htmlspecialchars($b['customerName'],ENT_QUOTES) . '</td>'
           . '<td>' . htmlspecialchars($b['Check_In_Date'],ENT_QUOTES) . '</td>'
           . '<td>' . htmlspecialchars($b['Check_Out_Date'],ENT_QUOTES) . '</td>'
           . '<td>' . htmlspecialchars($b['Status'],ENT_QUOTES) . '</td>'
           . "<td>{$pay}</td>"
           . '<td>'
           . '<button class="viewBookingBtn" data-id="'
           . htmlspecialchars((string)$b['Booking_ID'],ENT_QUOTES)
           . '">View Details</button>';

    switch ($b['Status']) {
        case 'Pending':
            $html .= ' <button class="updateStatusBtn" data-id="'
                   . (int)$b['Booking_ID']
                   . '" data-newstatus="Confirmed">Confirm</button>'
                   . ' <button class="updateStatusBtn" data-id="'
                   . (int)$b['Booking_ID']
                   . '" data-newstatus="Cancelled">Cancel</button>';
            break;
        case 'Confirmed':
            $html .= ' <button class="updateStatusBtn" data-id="'
                   . (int)$b['Booking_ID']
                   . '" data-newstatus="Completed">Complete</button>'
                   . ' <button class="updateStatusBtn" data-id="'
                   . (int)$b['Booking_ID']
                   . '" data-newstatus="Cancelled">Cancel</button>';
            break;
        case 'Cancelled':
            $html .= ' <button class="updateStatusBtn" data-id="'
                   . (int)$b['Booking_ID']
                   . '" data-newstatus="Pending">Reopen</button>';
            break;
    }

    $html .= '</td></tr>';
}

echo $html;
exit;
