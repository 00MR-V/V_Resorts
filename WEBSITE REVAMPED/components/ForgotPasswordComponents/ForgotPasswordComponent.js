document.addEventListener('DOMContentLoaded', function () {
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const forgotPasswordBtn = document.getElementById('forgotPasswordBtn'); 
    const closeForgotPasswordBtn = document.querySelector('.forgotPasswordCloseButton');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const forgotPasswordMessage = document.getElementById('forgotPasswordMessage');

  
    forgotPasswordBtn.addEventListener('click', function (e) {
        e.preventDefault();
        forgotPasswordModal.classList.add('active');
    });


    closeForgotPasswordBtn.addEventListener('click', function () {
        forgotPasswordModal.classList.remove('active');
    });

    
    window.addEventListener('click', function (e) {
        if (e.target === forgotPasswordModal) {
            forgotPasswordModal.classList.remove('active');
        }
    });

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
