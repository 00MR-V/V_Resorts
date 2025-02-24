document.addEventListener('DOMContentLoaded', () => {
    console.log("Admin Page Loaded!"); // Debugging log

    const navLinks = document.querySelectorAll('.nav-list a');
    const sections = document.querySelectorAll('.content-section');

    // Ensure Dashboard is visible on page load
    document.getElementById('dashboard').classList.add('active');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');

            // Check if it's an external page (e.g., Manage Properties)
            if (this.href.includes("AdminManageProperties.php")) {
                window.location.href = this.href; // Redirect to external page
                return;
            }

            // Hide all sections for internal navigation
            sections.forEach(section => {
                section.classList.remove('active');
                section.classList.add('hidden');
            });

            // Show the selected section
            const activeSection = document.getElementById(sectionId);
            if (activeSection) {
                activeSection.classList.add('active');
                activeSection.classList.remove('hidden');
                console.log("Showing Section: " + sectionId);
            } else {
                console.log("Error: Section not found!");
            }
        });
    });
});
