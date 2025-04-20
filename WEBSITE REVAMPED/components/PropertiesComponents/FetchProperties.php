<?php
// File: components/PropertiesComponents/FetchProperties.php

require_once __DIR__ . '/../../database/VResortsConnection.php';

// Read filters from the query string
$destination = trim($_GET['destination'] ?? '');
$check_in    = $_GET['check_in']   ?? '';
$check_out   = $_GET['check_out']  ?? '';
$guests      = $_GET['guests']     ?? '';

// Build dynamic WHERE clauses
$where  = [];
$params = [];

// 1) Location filter
if ($destination !== '') {
    $where[]                = "Location LIKE :destination";
    $params[':destination'] = "%{$destination}%";
}

// 2) Capacity (guests) filter
if ($guests !== '') {
    // assumes Capacity string begins with the number of adults
    $where[]           = "CAST(SUBSTRING_INDEX(Capacity, ' ', 1) AS UNSIGNED) >= :guests";
    $params[':guests'] = (int)$guests;
}

// 3) Availability filter (date-range)
if ($check_in !== '' && $check_out !== '') {
    // exclude any property with overlapping booking
    $where[] = "Property_ID NOT IN (
        SELECT Property_ID
        FROM booking
        WHERE NOT (
            Check_Out_Date < :check_in
            OR Check_In_Date > :check_out
        )
    )";
    $params[':check_in']  = $check_in;
    $params[':check_out'] = $check_out;
}

// Combine clauses
$whereSql = $where
    ? 'WHERE ' . implode(' AND ', $where)
    : '';

// Final query: filter + random order + limit
$sql = "
    SELECT *
    FROM property
    {$whereSql}
    ORDER BY RAND()
    LIMIT 7
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

return $properties;
