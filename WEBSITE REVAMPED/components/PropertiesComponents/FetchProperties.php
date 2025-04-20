<?php


require_once __DIR__ . '/../../database/VResortsConnection.php';


$destination = trim($_GET['destination'] ?? '');
$check_in    = $_GET['check_in']   ?? '';
$check_out   = $_GET['check_out']  ?? '';
$guests      = $_GET['guests']     ?? '';


$where  = [];
$params = [];


if ($destination !== '') {
    $where[]                = "Location LIKE :destination";
    $params[':destination'] = "%{$destination}%";
}


if ($guests !== '') {

    $where[]           = "CAST(SUBSTRING_INDEX(Capacity, ' ', 1) AS UNSIGNED) >= :guests";
    $params[':guests'] = (int)$guests;
}


if ($check_in !== '' && $check_out !== '') {
    
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


$whereSql = $where
    ? 'WHERE ' . implode(' AND ', $where)
    : '';


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
