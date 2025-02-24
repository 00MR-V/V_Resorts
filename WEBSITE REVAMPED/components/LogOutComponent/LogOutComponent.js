function logOutUser() {
    fetch('/V_Resorts/components/LogOutComponent/LogOutComponent.php', {
        method: 'POST',
    })
        .then(response => {
            if (response.ok) {
                // Redirect to the homepage
                window.location.href = '/V_Resorts/WEBSITE%20REVAMPED/Pages/HomePage/HomePage.php';
            } else {
                alert('Logout failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error logging out:', error);
        });
}
