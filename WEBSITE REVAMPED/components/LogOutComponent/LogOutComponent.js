function logOutUser() {
    fetch('/V_Resorts/WEBSITE%20REVAMPED/Components/LogOutComponent/LogOutComponent.php', {
        method: 'POST',
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        if (text.indexOf('successful') !== -1) {
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
