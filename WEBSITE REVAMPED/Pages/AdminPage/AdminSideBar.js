document.addEventListener('DOMContentLoaded', () => {
    console.log("Admin Page Loaded!"); // Debugging log

    const navLinks = document.querySelectorAll('.nav-list a');
    const sections = document.querySelectorAll('.content-section');

    // Ensure Dashboard is visible on page load
    document.getElementById('dashboard').classList.add('active');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Check if it's an external page (e.g., Manage Properties)
            if (this.href.includes("AdminManageProperties.php")) {
                window.location.href = this.href;
                return;
            }

            const sectionId = this.getAttribute('data-section');
            const activeSection = document.getElementById(sectionId);

            if (!activeSection) {
                console.log("Error: Section not found!");
                return;
            }

            // If the clicked section is already active, do nothing
            if (activeSection.classList.contains('active')) {
                console.log("Section already active: " + sectionId);
                return;
            }

            // Hide all sections
            sections.forEach(section => {
                section.classList.remove('active');
                section.classList.add('hidden');
            });

            // Show the selected section
            activeSection.classList.add('active');
            activeSection.classList.remove('hidden');
            console.log("Showing Section: " + sectionId);
        });
    });
});
