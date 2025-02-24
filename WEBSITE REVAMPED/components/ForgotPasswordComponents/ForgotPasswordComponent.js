document.addEventListener('DOMContentLoaded', function () {
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const forgotPasswordBtn = document.getElementById('forgotPasswordBtn'); // Button to open modal
    const closeForgotPasswordBtn = document.querySelector('.forgotPasswordCloseButton');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const forgotPasswordMessage = document.getElementById('forgotPasswordMessage');

    // Open modal
    forgotPasswordBtn.addEventListener('click', function (e) {
        e.preventDefault();
        forgotPasswordModal.classList.add('active');
    });

    // Close modal
    closeForgotPasswordBtn.addEventListener('click', function () {
        forgotPasswordModal.classList.remove('active');
    });

    // Close modal by clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === forgotPasswordModal) {
            forgotPasswordModal.classList.remove('active');
        }
    });

    // Handle form submission via AJAX
    forgotPasswordForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const email = document.getElementById('forgotEmailInput').value;

        fetch('../../database/ResetPassword.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    forgotPasswordMessage.textContent = data.message;
                    forgotPasswordMessage.className = 'success';
                } else {
                    forgotPasswordMessage.textContent = data.message;
                    forgotPasswordMessage.className = 'error';
                }
                forgotPasswordMessage.classList.remove('hidden');
            })
            .catch((error) => {
                forgotPasswordMessage.textContent = 'An error occurred. Please try again.';
                forgotPasswordMessage.className = 'error';
                forgotPasswordMessage.classList.remove('hidden');
            });
    });
});
