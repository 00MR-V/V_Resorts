document.addEventListener('DOMContentLoaded', () => {
    console.log("Admin Page Loaded!"); 

    const navLinks = document.querySelectorAll('.nav-list a');
    const sections = document.querySelectorAll('.content-section');
    
    
    const dashboardSection = document.getElementById('dashboard');
    if (dashboardSection) {
        dashboardSection.classList.add('active');
    } else {
        console.warn("Warning: #dashboard section not found on this page.");
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

          
            const targetHref = this.getAttribute('href');
            if (targetHref.includes(".php") && !targetHref.includes("#")) {
                window.location.href = targetHref; 
                return;
            }

           
            const sectionId = this.getAttribute('data-section');
            if (!sectionId) {
                console.warn("Warning: data-section attribute missing on link.");
                return;
            }

            const activeSection = document.getElementById(sectionId);
            if (!activeSection) {
                console.warn(`Warning: Section ${sectionId} not found.`);
                return;
            }

            
            if (activeSection.classList.contains('active')) {
                console.log("Section already active: " + sectionId);
                return;
            }

            
            sections.forEach(section => {
                section.classList.remove('active');
                section.classList.add('hidden');
            });

            
            activeSection.classList.add('active');
            activeSection.classList.remove('hidden');
            console.log("Showing Section: " + sectionId);
        });
    });
});
