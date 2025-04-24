<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database/VResortsConnection.php';

// Only declare this function if it doesn't already exist:
if (!function_exists('fetchProperties')) {
    function fetchProperties(
        string $destination,
        string $check_in,
        string $check_out,
        $guests
    ): array {
        global $pdo;

        // 1) Build WHERE clause
        $where  = [];
        $params = [];

        if ($destination !== '') {
            $where[]                = "Location LIKE :destination";
            $params[':destination'] = "%{$destination}%";
        }
        if ($guests !== '' && is_numeric($guests)) {
            $where[]           = "CAST(SUBSTRING_INDEX(Capacity, ' ', 1) AS UNSIGNED) >= :guests";
            $params[':guests'] = (int)$guests;
        }
        if ($check_in !== '' && $check_out !== '') {
            $where[] = "Property_ID NOT IN (
                SELECT Property_ID FROM booking
                WHERE NOT (
                    Check_Out_Date < :check_in
                    OR Check_In_Date > :check_out
                )
            )";
            $params[':check_in']  = $check_in;
            $params[':check_out'] = $check_out;
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // 2) Fetch raw rows
        $sql = "
            SELECT *
              FROM property
              {$whereSql}
             ORDER BY RAND()
             LIMIT 7
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3) Normalize types & encode photo once
        $props = [];
        foreach ($raw as $r) {
            $props[] = [
                'Property_ID'   => (int)   $r['Property_ID'],
                'Name'          => (string)$r['Name'],
                'Description'   => (string)$r['Description'],
                'Price'         => (float) $r['Price'],
                'propertyPhoto' => base64_encode($r['propertyPhoto'] ?? ''),
            ];
        }

        // 4) Load & validate JSON schema
        $schemaFile = __DIR__ . '/../../schemas/properties.schema.json';
        if (!is_file($schemaFile) || !is_readable($schemaFile)) {
            throw new \RuntimeException("Cannot load JSON schema at {$schemaFile}");
        }
        $schema = json_decode(file_get_contents($schemaFile), true);
        if (!isset($schema['items']['required'], $schema['items']['properties'])) {
            throw new \RuntimeException("Invalid schema format");
        }
        if (!validateAgainstSchema($props, $schema['items']['required'], $schema['items']['properties'])) {
            throw new \RuntimeException("Property data does not match schema");
        }

        return $props;
    }
}

if (!function_exists('validateAgainstSchema')) {
    function validateAgainstSchema(array $rows, array $requiredKeys, array $propDefs): bool {
        foreach ($rows as $row) {
            // check required fields
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $row)) {
                    return false;
                }
            }
            // check JSON type
            foreach ($propDefs as $key => $def) {
                if (!array_key_exists($key, $row)) {
                    continue;
                }
                $value = $row[$key];
                switch ($def['type']) {
                    case 'integer':
                        if (!is_int($value)) return false;
                        break;
                    case 'number':
                        if (!is_int($value) && !is_float($value)) return false;
                        break;
                    case 'string':
                        if (!is_string($value)) return false;
                        break;
                }
            }
        }
        return true;
    }
}

// Invoke and return for your existing require-style use:
return fetchProperties(
    trim($_GET['destination'] ?? ''),
    $_GET['check_in']   ?? '',
    $_GET['check_out']  ?? '',
    $_GET['guests']     ?? ''
);
