document.addEventListener("DOMContentLoaded", () => {
    const resetForm = document.getElementById("reset-password-form");

    resetForm.addEventListener("submit", (event) => {
        event.preventDefault(); // Prevent actual form submission

        const newPassword = document.getElementById("new-password").value;
        const confirmPassword = document.getElementById("confirm-password").value;

        if (newPassword === confirmPassword) {
            alert("Your password has been reset successfully!");
            // Redirect to the login page or another action
            window.location.href = "../../Pages/HomePage/HomePage.php";
        } else {
            alert("Passwords do not match. Please try again.");
        }
    });

    const closeButton = document.querySelector(".close-button");
    closeButton.addEventListener("click", (event) => {
        event.preventDefault();
        alert("Closing modal...");
        // Optionally, redirect to the previous page
        window.history.back();
    });
});
