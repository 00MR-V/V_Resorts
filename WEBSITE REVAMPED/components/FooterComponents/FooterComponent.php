<?php
$current_page = basename($_SERVER['PHP_SELF']); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V Resorts - Footer</title>
</head>

<body>
    <footer>
        <div class="newsletterContainer">
            <div class="newsletterText">
                Subscribe to our newsletter
            </div>
            <div class="newsletterBox">
                <div class="newsletterBox1">
                    <img src="../../images/Email.png" alt="Email Icon" id="newsletterEmailIcon">
                    <input type="text" id="newsletterEmailInput" placeholder="Input your email" />
                </div>
                <div class="newsletterBox2">
                    <div>
                        Subscribe
                    </div>
                </div>
            </div>
        </div>

        <div class="footerList">
            <div class="logoContainer">
                <div class="logoImage">
                    <img src="../../Images/logoImage.png" alt="logo" />
                </div>

                <div class="companyName">
                    V Resorts
                </div>
            </div>

            <ul class="footerList">
                <li>
                    <a href="../../Pages/PropertiesPage/PropertiesPage.php">
                        PROPERTIES
                    </a>
                </li>
                <li>
                    <a href="../../Pages/AboutUsPage/AboutUsPage.php">
                        ABOUT US
                    </a>
                </li>
                <li>
                    <a href="../../Pages/ContactUsPage/ContactUsPage.php">
                        CONTACT US
                    </a>
                </li>
                <li>
                    <a href="../../Pages/FAQsPage/FAQsPage.php">
                        FAQs
                    </a>
                </li>
                <li>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- Admin Link -->
                        <a href="/WEBSITE%20REVAMPED/Pages/AdminPage/AdminPage.php" id="managePropertyBtn">
                            MANAGE YOUR PROPERTY
                        </a>
                    <?php else: ?>
                        <!-- Customer Link -->
                        <a href="#" id="rentYourPropertyBtn">
                            RENT YOUR PROPERTY
                        </a>
                    <?php endif; ?>
                </li>
                <!-- <li>
                    <a href="V_Resorts About Us.php">
                        CAREERS
                    </a>
                </li> -->
            </ul>
        </div>


        <div class="whiteLine"></div>

        <div class="footerLastLine">
            <div class="languageSelector">
                <select id="languageDropdown" onchange="changeLanguage()">
                    <option value="en" selected>English</option>
                    <option value="fr"> French</option>
                    <option value="hi"> Hindi</option>
                </select>
            </div>

            <div class="middleTextInFooterLine">
                © 2022 Brand, Inc. • <a href="#">Privacy</a> • <a href="#">Terms</a> • <a href="#">Sitemap</a>
            </div>
            <div class="socialIcons">
                <a href="#"><img src="../../images/Twitter.png" alt="twitter"></a>
                <a href="#"><img src="../../images/Facebook.png" alt="Facebook"></a>
                <a href="#"><img src="../../images/LinkedIn.png" alt="LinkedIn"></a>
                <a href="#"><img src="../../images/YouTube.png" alt="YouTube"></a>
            </div>

        </div>


    </footer>

</body>

</html>