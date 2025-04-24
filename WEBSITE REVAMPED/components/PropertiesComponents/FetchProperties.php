<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../database/VResortsConnection.php';

use Opis\JsonSchema\Validator;

if (! function_exists('fetchProperties')) {
    function fetchProperties(string $dest, string $ci, string $co, $guests): array
    {
        global $pdo;
        $where  = [];
        $params = [];

        if ($dest !== '') {
            $where[]           = "Location LIKE :dest";
            $params[':dest']   = "%{$dest}%";
        }

        if ($guests !== '' && is_numeric($guests)) {
            $where[]           = "CAST(SUBSTRING_INDEX(Capacity,' ',1) AS UNSIGNED) >= :guests";
            $params[':guests'] = (int)$guests;
        }

        if ($ci !== '' && $co !== '') {
            $where[]            = "Property_ID NOT IN (
                SELECT Property_ID FROM booking
                WHERE NOT (
                    Check_Out_Date < :ci
                    OR Check_In_Date > :co
                )
            )";
            $params[':ci']      = $ci;
            $params[':co']      = $co;
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt     = $pdo->prepare("SELECT * FROM property {$whereSql} ORDER BY RAND() LIMIT 7");
        $stmt->execute($params);
        $raw      = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $props = [];
        foreach ($raw as $r) {
            $props[] = [
                'Property_ID'   => (int)$r['Property_ID'],
                'Name'          => (string)$r['Name'],
                'Description'   => (string)$r['Description'],
                'Price'         => (float)$r['Price'],
                'propertyPhoto' => base64_encode($r['propertyPhoto'] ?? ''),
            ];
        }

        $schemaJson = @file_get_contents(__DIR__ . '/../../schemas/properties.schema.json');
        $schemaData = json_decode($schemaJson);
        $validator  = new Validator();
        $toValidate = json_decode(json_encode($props));
        $result     = $validator->validate($toValidate, $schemaData);

        if (! $result->isValid()) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Schema validation failed']);
            exit;
        }

        return $props;
    }
}

return fetchProperties(
    trim($_GET['destination'] ?? ''),
    $_GET['check_in']   ?? '',
    $_GET['check_out']  ?? '',
    $_GET['guests']     ?? ''
);
