document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');

    // Function to handle the search (e.g., redirect to search results page)
    function handleSearch() {
        const query = searchInput.value.trim(); // Get the search query
        if (query) {
            console.log("Searching for:", query);
            // You can redirect to a search results page or handle the search in another way
            window.location.href = `search_results.php?q=${encodeURIComponent(query)}`;
        }
    }

    // Event listener for pressing "Enter"
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });

    // Event listener for clicking the search icon
    searchIcon.addEventListener('click', function () {
        handleSearch();
    });
});
