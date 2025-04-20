<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V Resorts - FAQs</title>
  <link rel="stylesheet" href="FAQsPage.css"> <!-- Link to the CSS file -->
  <script src="../../Pages/FAQsPage/FAQsPage.js"></script>

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
  $show_search_box = false; 
  include '../../components/HeaderComponents/HeaderComponent.php';
  ?>

  <section id="faqs">
    <div class="faq-container">
      <h2>Frequently Asked Questions</h2>

      <div class="faq-item">
        <div class="faq-question">
          What is V Resorts?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">V Resorts is a platform for discovering and booking luxury resorts and villas in Mauritius.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          How do I book a villa?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Simply search for available properties, choose your desired dates, and proceed to book through our secure system.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Are the properties verified?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Yes, all properties listed on V Resorts are thoroughly verified for quality and authenticity.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Can I list my property on V Resorts?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Yes, property owners and agents can list their properties by signing up and following the listing process.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Is my payment secure?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Yes, we use industry-standard encryption to ensure your payment information is safe.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Can I cancel a booking?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Cancellations are allowed, but policies vary depending on the property. Please review the terms before booking.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          How do I contact customer support?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">You can reach us via email at support@vresorts.com or by calling +230 123 4567.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          What amenities are included in the villas?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Amenities vary by property, but most include essentials like Wi-Fi, air conditioning, and more.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Is there a minimum stay requirement?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Yes, minimum stays vary depending on the property. Check the details on the listing page.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Can I modify my booking after confirmation?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Modifications depend on the property's policy. Contact support for assistance.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Do I need to pay a deposit?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Some properties require a deposit. Details are mentioned on the property page.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Are pets allowed?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Pet policies vary by property. Check the specific listing for details.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          What is the check-in and check-out time?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Standard check-in is at 3 PM and check-out at 11 AM, but it may vary by property.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          How do I leave a review?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">After your stay, you'll receive an email prompting you to leave a review for the property.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          Can I host events at the villas?
          <span class="faq-toggle-sign">+</span>
        </div>
        <div class="faq-answer">Event hosting policies vary. Please confirm with the property owner before booking.</div>
      </div>
    </div>
  </section>

  <?php include '../../components/FooterComponents/FooterComponent.php'; ?>


  <?php include '../../components/LogInModal/LogInModal.php'; ?>
  <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
  <?php include '../../components/SignUpModal/AdminSignUpModal.php'; ?>
</body>

</html>