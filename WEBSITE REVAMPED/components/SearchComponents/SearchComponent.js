
document.addEventListener('DOMContentLoaded', () => {
    const destinationInput = document.getElementById('search-destination');
    const checkInInput     = document.getElementById('check-in-date');
    const checkOutInput    = document.getElementById('check-out-date');
    const guestInput       = document.getElementById('guest-count');
    const searchButton     = document.querySelector('.search-button');

   
    if (destinationInput) {
        destinationInput.focus({ preventScroll: true });
    }

    function doSearch() {
        const destination = encodeURIComponent(destinationInput.value.trim());
        const checkIn     = encodeURIComponent(checkInInput.value);
        const checkOut    = encodeURIComponent(checkOutInput.value);
        const guestCount  = encodeURIComponent(guestInput.value.trim());

        const paramsArr = [];
        if (destination) paramsArr.push(`destination=${destination}`);
        if (checkIn)     paramsArr.push(`check_in=${checkIn}`);
        if (checkOut)    paramsArr.push(`check_out=${checkOut}`);
        if (guestCount)  paramsArr.push(`guests=${guestCount}`);

        const qs = paramsArr.length ? `?${paramsArr.join('&')}` : '';
        window.location.href = `/V_Resorts/WEBSITE REVAMPED/Pages/PropertiesPage/PropertiesPage.php${qs}`;
    }

    
    searchButton.addEventListener('click', doSearch);
    [destinationInput, checkInInput, checkOutInput, guestInput].forEach(input => {
        input.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                doSearch();
            }
        });
    });
});
