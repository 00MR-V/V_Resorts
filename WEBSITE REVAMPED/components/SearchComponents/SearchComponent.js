document.addEventListener('DOMContentLoaded', () => {
    const destinationInput = document.getElementById('search-destination');
    const checkInInput = document.getElementById('check-in-date');
    const checkOutInput = document.getElementById('check-out-date');
    const guestInput = document.getElementById('guest-count');
    const searchButton = document.querySelector('.search-button');

    searchButton.addEventListener('click', () => {
        const destination = destinationInput.value || "Not specified";
        const checkIn = checkInInput.value || "Not specified";
        const checkOut = checkOutInput.value || "Not specified";
        const guestCount = guestInput.value || "Not specified";

        // Log the search details
        console.log('Search Details:', {
            destination,
            checkIn,
            checkOut,
            guestCount
        });

        // Example: Alert the user with the search information
        alert(`Search Details:
        Destination: ${destination}
        Check-In: ${checkIn}
        Check-Out: ${checkOut}
        Guests: ${guestCount}`);
    });
});
