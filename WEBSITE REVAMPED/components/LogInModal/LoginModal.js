
var logInModal = document.getElementById("logInModal"); 
var signUpModal = document.getElementById("signUpModal");
var logInBtn = document.getElementById("logInBtn");
var closeModalBtn = document.getElementsByClassName("logInCloseButton")[0];
var switchToSignUpBtn = document.querySelector(".logInGetStartedButton");


logInBtn.onclick = function() {
    logInModal.style.display = "block"; 
}


closeModalBtn.onclick = function() {
    logInModal.style.display = "none"; 
}


window.onclick = function(event) {
    if (event.target == logInModal) { 
        logInModal.style.display = "none"; 
    }
}


switchToSignUpBtn.onclick = function (event) {
    event.preventDefault(); 
    logInModal.style.display = "none"; 
    signUpModal.style.display = "block";
};
