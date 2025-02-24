<?php
$current_page = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  
  <script src="../../components/LogInModal/LogInModal.js"></script>

</head>

<body>

</body>

</html>
<div id="logInModal" class="logInoverlay">
  <div class="LogInformContainer">
    <a href="#" class="logInCloseButton">&times;</a>
    <h2>Log In or Sign Up</h2>
    <div class="logInGrayLineTop"></div>
    <h1>Welcome to V Resorts</h1>
    <p>Enter your account details below</p>

    <form method="POST" action="../../database/SignInDBConnection.php">
      <div class="inputGroup">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Email Address" required>
      </div>
      <div class="inputGroup">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>
      <button type="submit" class="logInSignInButton">Log In</button><br><br>
      <a href="#" id="forgotPasswordBtn" class="forgotPasswordLink">Forgot your password?</a>

    </form>
    <div class="logInGrayLineBottomWithOr">
      <span>OR</span>
    </div>

    <div class="signUpSection">
      <p>Don't have an account?</p>
      <a href="#">
        <button class="logInGetStartedButton">Get Started</button>
      </a>

    </div>
  </div>
</div>