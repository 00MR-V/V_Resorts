document.addEventListener("DOMContentLoaded", function () {
 
    var adminSignUpModal = document.getElementById("adminSignUpModal");
    var rentYourPropertyBtn = document.getElementById("rentYourPropertyBtn");
    var closeAdminSignUpBtn = document.querySelector(".adminSignUpCloseButton");
    var adminOpenLogInModal = document.getElementById("adminOpenLogInModal");
    var adminLogInModal = document.getElementById("logInModal");

   
    window.onload = function () {
        if (adminSignUpModal) {
            adminSignUpModal.style.display = "none"; 
        }
    };

    
    if (rentYourPropertyBtn) {
        rentYourPropertyBtn.onclick = function (event) {
            event.preventDefault(); 
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "block"; 
            }
        };
    }

   
    if (closeAdminSignUpBtn) {
        closeAdminSignUpBtn.onclick = function () {
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "none"; 
            }
        };
    }

   
    if (adminOpenLogInModal) {
        adminOpenLogInModal.onclick = function (event) {
            event.preventDefault(); 
            if (adminSignUpModal) {
                adminSignUpModal.style.display = "none"; 
            }
            if (adminLogInModal) {
                adminLogInModal.style.display = "block"; 
            }
        };
    }


    window.onclick = function (event) {
        if (event.target === adminSignUpModal) {
            adminSignUpModal.style.display = "none";
        }
    };

   
    
});
