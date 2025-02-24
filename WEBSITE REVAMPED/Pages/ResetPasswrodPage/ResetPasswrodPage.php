<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../../Pages/ResetPasswrodPage/ResetPasswrodPage.css""> <!-- Link to CSS -->
    <script src="../../Pages/ResetPasswrodPage/ResetPasswrodPage.js" defer></script> <!-- Link to JS -->

</head>

<body>
    <div class="reset-password-modal">
        <div class="modal-content">
            <a href="#" class="close-button">&times;</a>
            <h2>Reset Your Password</h2>
            <p>Please enter your new password below.</p>
            <form id="reset-password-form">
                <div class="input-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" placeholder="Enter new password" required>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="reset-button">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>
