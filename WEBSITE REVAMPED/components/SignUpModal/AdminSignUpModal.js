document.addEventListener("DOMContentLoaded", function () {
    // Get all necessary modal elements and buttons
    var adminSignUpModal = document.getElementById("adminSignUpModal");
    var rentYourPropertyBtn = document.getElementById("rentYourPropertyBtn");
    var closeAdminSignUpBtn = document.querySelector(".adminSignUpCloseButton");
    var adminOpenLogInModal = document.getElementById("adminOpenLogInModal");
    var adminLogInModal = document.getElementById("logInModal");

    // Ensure the admin sign-up modal is hidden on page load
    window.onload = function () {
        if (adminSignUpModal) {
            adminSignUpModal.style.display = "none"; // Hide admin sign-up modal initially
        }
    };

    // Show Admin Sign Up Modal when "Rent Your Property" link is clicked
    if (rentYourPropertyBtn) {
        rentYourPropertyBtn.onclick = function (event) {
            event.preventDefault(); // Prevent default link behavior
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "block"; // Show the admin sign-up modal
            }
        };
    }

    // Close Admin Sign Up Modal when the close button is clicked
    if (closeAdminSignUpBtn) {
        closeAdminSignUpBtn.onclick = function () {
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "none"; // Close the admin sign-up modal
            }
        };
    }

    // Switch to Admin Log In Modal when "Log In" button is clicked
    if (adminOpenLogInModal) {
        adminOpenLogInModal.onclick = function (event) {
            event.preventDefault(); // Prevent default link behavior
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "none"; // Hide admin sign-up modal
            }
            if (adminLogInModal) {
                adminLogInModal.style.display = "block"; // Show admin log-in modal
            }
        };
    }

    // Close Admin Sign Up Modal if clicked outside the modal
    window.onclick = function (event) {
        if (event.target === adminSignUpModal) {
            adminSignUpModal.style.display = "none"; // Close the admin sign-up modal if clicked outside
        }
    };

   
    
});
