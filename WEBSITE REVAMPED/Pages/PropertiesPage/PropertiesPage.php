<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V Resorts - Contact Us</title>
  <link rel="stylesheet" href="PropertiesPage.css"> <!-- Link to your CSS file -->

  <link rel="stylesheet" type="text/css" href="../../components/HeaderComponents/HeaderComponent.css">
  <script src="../../components/HeaderComponents/HeaderComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/LogInModal/LogInModal.css">
  <script src="../../components/LogInModal/LogInModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/SignUpModal.css">
  <script src="../../components/SignUpModal/SignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/AdminSignUpModal.css">
  <script src="../../components/SignUpModal/AdminSignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SearchComponents/SearchComponent.css">
  <script src="../../components/SearchComponents/SearchComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/PropertiesComponents/Properties.css">

  <script src="../../components/LogOutComponent/LogOutComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/FooterComponents/FooterComponent.css">
</head>

<body>
  <?php
  $show_search_box = false; // Set this to false to hide the search box
  include '../../components/HeaderComponents/HeaderComponent.php';
  ?>
  <?php include '../../components/SearchComponents/SearchComponent.php'; ?>

  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/PropertiesComponents/Properties.php'; ?>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>

  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>

</html>