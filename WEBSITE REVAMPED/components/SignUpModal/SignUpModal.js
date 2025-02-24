
var signUpModal = document.getElementById("signUpModal");
var logInModal = document.getElementById("logInModal"); 
var signUpBtn = document.getElementById("signUpBtn");
var closeSignUpBtn = document.getElementsByClassName("signUpCloseButton")[0];
var switchToLogInBtn = document.getElementById("openLogInModal"); 


signUpBtn.onclick = function () {
    signUpModal.style.display = "block";
};


closeSignUpBtn.onclick = function () {
    signUpModal.style.display = "none";
};


switchToLogInBtn.onclick = function (event) {
    event.preventDefault(); 
    signUpModal.style.display = "none"; 
    logInModal.style.display = "block";
};


window.onclick = function (event) {
    if (event.target == signUpModal) {
        signUpModal.style.display = "none";
    }
};
