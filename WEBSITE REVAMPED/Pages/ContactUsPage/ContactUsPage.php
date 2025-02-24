<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V Resorts - Contact Us</title>
  <link rel="stylesheet" href="ContactUsPage.css"> <!-- Link to your CSS file -->

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

  <section id="contact-us">
    <div class="contact-container">
      <h2>Contact Us</h2>
      <p>If you have any questions, feedback, or need assistance, feel free to reach out to us. Our team is here to help you!</p>

      <div class="contact-form-container">
        <form action="submitContactForm.php" method="post" class="contact-form">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
          </div>

          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
          </div>

          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="submit-button">Send Message</button>
          </div>
        </form>
      </div>

      <div class="contact-details">
        <h3>Our Contact Information</h3>
        <p><strong>Email:</strong> support@vresorts.com</p>
        <p><strong>Phone:</strong> +230 123 4567</p>
        <p><strong>Address:</strong> North Coast, Mauritius</p>
      </div>
    </div>
  </section>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>

  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>

</html>