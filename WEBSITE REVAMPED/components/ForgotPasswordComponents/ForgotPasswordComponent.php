<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="forgotPasswordModal" class="forgot-password-overlay">
    <div class="forgotPasswordContainer">
        <a href="#" class="forgotPasswordCloseButton">&times;</a>
        <h2>Forgot Password</h2>
        <p>Enter your email address, and we'll send you a link to reset your password.</p>

        <form id="forgotPasswordForm" method="POST">
            <div class="inputGroup">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="forgotEmailInput" placeholder="Email Address" required>
            </div>
            <button type="submit" class="submitForgotPassword">Send Reset Link</button>
        </form>

        <div id="forgotPasswordMessage" class="hidden"></div> <!-- Success/Error message -->
    </div>
</div>
