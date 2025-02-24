<?php
$current_page = basename($_SERVER['PHP_SELF']); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V Resorts - Home</title>

  <link rel="stylesheet" href="HomePage.css">

  <link rel="stylesheet" type="text/css" href="../../components/HeaderComponents/HeaderComponent.css">
  <script src="../../components/HeaderComponents/HeaderComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/LogInModal/LogInModal.css">
  <script src="../../components/LogInModal/LogInModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/SignUpModal.css">
  <script src="../../components/SignUpModal/SignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/AdminSignUpModal.css">
  <script src="../../components/SignUpModal/AdminSignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/ForgotPasswordComponents/ForgotPasswordComponent.css">
  <script src="../../components/ForgotPasswordComponents/ForgotPasswordComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/PropertiesComponents/Properties.css">

  <script src="../../components/LogOutComponent/LogOutComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/FooterComponents/FooterComponent.css">

</head>

<body>
  <?php include '../../components/HeaderComponents/HeaderComponent.php'; ?>


  <div class="background-container">
    <img src="../../images/HomePageImage.png" alt="Home Background Picture" class="background">

    <div class="backgoundImageText1">
      Our Villa
    </div>

    <div class="backgoundImageText2">
      Discover luxury and comfort in our exclusive resort villas
    </div>

    <div class="exploreButton">
      <a href="../PropertiesPage/PropertiesPage.php">Explore Now</a>
    </div>
  </div>

  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/PropertiesComponents/Properties.php'; ?>
  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>


  
  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
  <?php include '../../components/ForgotPasswordComponents/ForgotPasswordComponent.php'; ?>



</body>

</html>