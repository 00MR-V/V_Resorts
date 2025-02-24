<?php
$current_page = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <script src="../../components/SignUpModal/SignUpModal.js"></script>

</head>

<body>

    <div id="signUpModal" class="signUpOverlay">
        <div class="signUpFormContainer">
            <a href="#" class="signUpCloseButton">&times;</a>
            <h2>Log In or Sign Up</h2>
            <div class="signUpGrayLineTop"></div>
            <h1>Welcome to V Resorts</h1>
            <p>Enter your account details below</p>

            <form method="POST" action="../../database/SignUpDBConnection.php">
                <div class="inputGroupTwo">
                    <div class="inputItem">
                        <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
                    </div>
                    <div class="inputItem">
                        <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="inputGroupTwo">
                    <div class="inputItem">
                        <input type="date" name="dob" id="dob" placeholder="Date of Birth" required>
                    </div>
                    <div class="inputItem">
                        <input type="text" name="address" id="address" placeholder="Address" required>
                    </div>
                </div>
                <div class="inputGroupTwo">
                    <div class="inputItem">
                        <input type="tel" name="phone" id="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="inputItem">
                        <input type="text" name="username" id="username" placeholder="Username" required>
                    </div>
                </div>
                <div class="inputGroupTwo">
                    <div class="inputItem">
                        <input type="email" name="email" id="email" placeholder="Email Address" required>
                    </div>
                    <div class="inputItem">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="inputGroupTwo">
                    <div class="inputItem">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="signUpButton">Sign Up</button>


            </form>
            <div class="logInGrayLineBottomWithOr">
                <span>OR</span>
            </div>
            <div class="login-section">
                <p>Already have an account?</p>
                <a href="#" id="openLogInModal">
                    <button class="sign-in-btn">Log In</button>
                </a>
            </div>
        </div>
    </div>

</body>

</html>