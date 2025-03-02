<?php
require_once __DIR__ . '/../../database/VResortsConnection.php';

// Number of properties per container
$propertiesPerContainer = 7;

// Fetch random properties for this inclusion
$query = $pdo->prepare("SELECT * FROM property ORDER BY RAND() LIMIT ?");
$query->bindValue(1, $propertiesPerContainer, PDO::PARAM_INT);
$query->execute();

// Fetch the properties
$properties = $query->fetchAll(PDO::FETCH_ASSOC);

// Return the properties
return $properties;
