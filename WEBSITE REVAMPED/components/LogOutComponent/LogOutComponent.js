function logOutUser() {
    fetch('../../components/LogOutComponent/LogOutComponent.php', {
        method: 'POST', // Use POST for better semantics
    })
    .then(response => {
        if (response.ok) {
            // Redirect to the homepage
            window.location.href = '/WEBSITE%20REVAMPED/Pages/HomePage/HomePage.php';
        } else {
            alert('Logout failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error logging out:', error);
    });
}
