<?php
// File: Pages/PropertiesPage/PropertiesPage.php

// Capture filters from query string
$show_search_box = false; // hide header search box
$destination = trim($_GET['destination'] ?? '');
$check_in    = $_GET['check_in']   ?? '';
$check_out   = $_GET['check_out']  ?? '';
$guests      = $_GET['guests']     ?? '';

// Determine if this is a filtered search
$isSearch = ($destination !== '' || $check_in !== '' || $check_out !== '' || $guests !== '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>V Resorts – Properties</title>
  <link rel="stylesheet" href="PropertiesPage.css">

  <!-- Header & Modals -->
  <link rel="stylesheet" href="../../components/HeaderComponents/HeaderComponent.css">
  <script src="../../components/HeaderComponents/HeaderComponent.js" defer></script>
  <link rel="stylesheet" href="../../components/LogInModal/LogInModal.css">
  <script src="../../components/LogInModal/LogInModal.js" defer></script>
  <link rel="stylesheet" href="../../components/SignUpModal/SignUpModal.css">
  <script src="../../components/SignUpModal/SignUpModal.js" defer></script>
  <link rel="stylesheet" href="../../components/SignUpModal/AdminSignUpModal.css">
  <script src="../../components/SignUpModal/AdminSignUpModal.js" defer></script>
  <script src="../../components/LogOutComponent/LogOutComponent.js" defer></script>

  <!-- Search & Properties -->
  <link rel="stylesheet" href="../../components/SearchComponents/SearchComponent.css">
  <script src="../../components/SearchComponents/SearchComponent.js" defer></script>
  <link rel="stylesheet" href="../../components/PropertiesComponents/Properties.css">

  <!-- Footer -->
  <link rel="stylesheet" href="../../components/FooterComponents/FooterComponent.css">
</head>
<body>
  <?php include '../../components/HeaderComponents/HeaderComponent.php'; ?>

  <!-- Search Bar -->
  <?php
    // send current filter values into the search inputs
    $_SERVER['destination_value'] = htmlspecialchars($destination, ENT_QUOTES);
    $_SERVER['checkin_value']     = htmlspecialchars($check_in,   ENT_QUOTES);
    $_SERVER['checkout_value']    = htmlspecialchars($check_out,  ENT_QUOTES);
    $_SERVER['guest_value']       = htmlspecialchars($guests,     ENT_QUOTES);
  ?>
  <?php include '../../components/SearchComponents/SearchComponent.php'; ?>

  <!-- Properties List -->
  <main class="properties-section">
    <?php
      if (!$isSearch) {
        // No filters: show 5 containers
        for ($i = 0; $i < 5; $i++) {
          include '../../components/PropertiesComponents/Properties.php';
        }
      } else {
        // Filtered: show just one container with your server‐side filtering logic
        include '../../components/PropertiesComponents/Properties.php';
      }
    ?>
  </main>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>
  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>
</html>
