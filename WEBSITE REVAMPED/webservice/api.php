<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../database/VResortsConnection.php';

use Opis\JsonSchema\Validator;

// 1) routing by action
$action = $_GET['action'] ?? '';
if ($action !== 'properties') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error'=>'Invalid action']);
    exit;
}

// 2) pull & sanitize
$dest   = trim($_GET['destination']  ?? '');
$ci     = $_GET['check_in']          ?? '';
$co     = $_GET['check_out']         ?? '';
$guests = $_GET['guests']            ?? '';

// 3) build SQL
$where  = []; $params = [];
if ($dest!=='') {
    $where[]            = "Location LIKE :dest";
    $params[':dest']    = "%{$dest}%";
}
if ($ci!=='' && $co!=='') {
    $where[] = "Property_ID NOT IN (
        SELECT Property_ID FROM booking
        WHERE NOT (Check_Out_Date < :ci OR Check_In_Date > :co)
    )";
    $params[':ci'] = $ci;
    $params[':co'] = $co;
}
if (is_numeric($guests)) {
    $where[]            = "CAST(SUBSTRING_INDEX(Capacity,' ',1) AS UNSIGNED) >= :g";
    $params[':g']       = (int)$guests;
}
$sql = 'SELECT * FROM property'
     . ($where ? ' WHERE '.implode(' AND ',$where) : '')
     . ' ORDER BY RAND() LIMIT 7';

// 4) fetch + normalize
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$props = array_map(fn($r)=>[
    'Property_ID'   => (int)$r['Property_ID'],
    'Name'          => $r['Name'],
    'Description'   => $r['Description'],
    'Price'         => (float)$r['Price'],
    'propertyPhoto' => base64_encode($r['propertyPhoto'] ?? ''),
], $raw);

// 5) server-side JSON-Schema validation
$schemaJson = @file_get_contents(__DIR__.'/../schemas/properties.schema.json');
$schema     = json_decode($schemaJson);
$validator  = new Validator();
$result     = $validator->validate(json_decode(json_encode($props)), $schema);
if (!$result->isValid()) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error'=>'Schema validation failed']);
    exit;
}

// 6) return JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($props, JSON_UNESCAPED_UNICODE);
