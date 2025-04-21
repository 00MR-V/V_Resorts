<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);


$show_search_box = isset($show_search_box) ? $show_search_box : true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V Resorts Header</title>
</head>

<body>
    <header>
        <div class="logoContainer">
            <div class="logoImage">
                <img src="../../Images/logoImage.png" alt="logo" />
            </div>

            <div class="companyName">
                V Resorts
            </div>
        </div>

        <ul class="headerList">
            <li>
                <a href="../../Pages/HomePage/HomePage.php">
                    HOME
                </a>
            </li>
            <li>
                <a href="../../Pages/PropertiesPage/PropertiesPage.php">
                    PROPERTIES
                </a>
            </li>
            <li>
                <a href="../../Pages/MyBookingsPage/MyBookingsPage.php">
                    MY BOOKINGS
                </a>
            </li>
        </ul>

        <!-- Conditionally display search box -->
        <?php if ($show_search_box): ?>
            <div class="searchBox">
                <div class="searchBox1">
                    <input type="text" id="searchInput" placeholder="Search..." />
                </div>
                <div class="searchBox2">
                    <img src="../../images/SearchLogo.png" alt="Search Icon" id="searchIcon">
                </div>
            </div>
        <?php endif; ?>

        <div class="userAuth">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- User is logged in -->
                <div class="logIn" style="font-size: 20px;">
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- Admin user: clickable link -->
                        <a href="/V_Resorts/WEBSITE REVAMPED/Pages/AdminPage/AdminPage.php" style="color: white; text-decoration: none;">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                    <?php else: ?>
                        <!-- Customer user: non-clickable -->
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="signUp">
                    <a href="#" id="logOutBtn" onclick="logOutUser()">Log Out</a>
                </div>
            <?php else: ?>
                <!-- User is not logged in -->
                <div class="logIn">
                    <a href="#" id="logInBtn">Log In</a>
                </div>
                <div class="signUp">
                    <a href="#" id="signUpBtn">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php include '../../components/LogInModal/LogInModal.php'; ?>
    <?php include '../../components/SignUpModal/SignUpModal.php'; ?>
</body>

</html>