// components/HeaderComponents/HeaderComponent.js
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchIcon  = document.getElementById('searchIcon');

    // Redirect into the big search on PropertiesPage
    function forwardToBigSearch() {
        const q = searchInput.value.trim();
        const base = '/V_Resorts/WEBSITE%20REVAMPED/Pages/PropertiesPage/PropertiesPage.php';
        // build URL without fragment
        const url = q
            ? `${base}?destination=${encodeURIComponent(q)}`
            : base;
        window.location.href = url;
    }

    // Enter in the header box
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            forwardToBigSearch();
        }
    });

    // Click the header search icon
    searchIcon.addEventListener('click', function () {
        forwardToBigSearch();
    });

    // On focus of the header box, go to big search
    searchInput.addEventListener('focus', forwardToBigSearch);
});
