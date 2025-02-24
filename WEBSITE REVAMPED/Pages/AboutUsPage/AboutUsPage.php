<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V Resorts - About Us</title>
  <link rel="stylesheet" href="AboutUsPage.css"> <!-- Link to your CSS file -->


  <link rel="stylesheet" type="text/css" href="../../components/HeaderComponents/HeaderComponent.css">
  <script src="../../components/HeaderComponents/HeaderComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/LogInModal/LogInModal.css">
  <script src="../../components/LogInModal/LogInModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/SignUpModal.css">
  <script src="../../components/SignUpModal/SignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/SignUpModal/AdminSignUpModal.css">
  <script src="../../components/SignUpModal/AdminSignUpModal.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/PropertiesComponents/Properties.css">

  <script src="../../components/LogOutComponent/LogOutComponent.js"></script>

  <link rel="stylesheet" type="text/css" href="../../components/FooterComponents/FooterComponent.css">
</head>

<body>
  <?php
  $show_search_box = false; // Set this to false to hide the search box
  include '../../components/HeaderComponents/HeaderComponent.php';
  ?>

  <section id="about-us">
    <div class="about-container">
      <h2>About V Resorts</h2>
      <p>Welcome to <strong>V Resorts</strong>, the ultimate destination for discovering and booking luxury resorts and villas in Mauritius. Our platform connects travelers with premium properties to make your dream vacation a reality.</p>

      <div class="section">
        <h3>Our Vision</h3>
        <p>We aim to redefine how travelers experience Mauritius by providing an easy, seamless process to book exclusive properties. Whether you're looking for a serene beachfront villa, a cozy mountain retreat, or a luxurious penthouse, we offer a diverse range of options for every type of traveler.</p>
      </div>

      <div class="section">
        <h3>Why Choose V Resorts?</h3>
        <ul>
          <li>Exclusive properties, handpicked for their charm and comfort.</li>
          <li>Detailed listings with high-quality photos and genuine guest reviews.</li>
          <li>Advanced search filters for personalized vacation planning.</li>
          <li>Easy-to-use booking system and secure payment options.</li>
          <li>24/7 customer support to assist you with any queries.</li>
        </ul>
      </div>

      <div class="section">
        <h3>For Property Owners</h3>
        <p>If you're a property owner or real estate agent, <strong>V Resorts</strong> provides a powerful platform to showcase your property to a global audience. Manage bookings, respond to inquiries, and increase your rental potential with our easy-to-use tools.</p>
      </div>

      <div class="section">
        <h3>Our Mission</h3>
        <p>Our mission is to create a trustworthy, transparent, and user-friendly platform for property bookings. We are committed to helping our guests find the perfect property and ensuring property owners get the most out of their listings.</p>
      </div>

      <div class="section">
        <h3>Join Us</h3>
        <p>Start your journey with <strong>V Resorts</strong> today and discover your ideal getaway. Whether you’re looking to book a relaxing vacation or list your property for rent, we are here to help you every step of the way.</p>
      </div>
    </div>
  </section>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>


  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>

</html>