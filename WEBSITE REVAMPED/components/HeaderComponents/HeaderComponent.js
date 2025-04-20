
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchIcon  = document.getElementById('searchIcon');

   
    function forwardToBigSearch() {
        const q = searchInput.value.trim();
        const base = '/V_Resorts/WEBSITE%20REVAMPED/Pages/PropertiesPage/PropertiesPage.php';
     
        const url = q
            ? `${base}?destination=${encodeURIComponent(q)}`
            : base;
        window.location.href = url;
    }


    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            forwardToBigSearch();
        }
    });

 
    searchIcon.addEventListener('click', function () {
        forwardToBigSearch();
    });


    searchInput.addEventListener('focus', forwardToBigSearch);
});
