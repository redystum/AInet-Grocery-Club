const mobileMenuButton = document.getElementById('mobileMenuButton');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
const toggleIcon = document.getElementById('toggleIcon');
const mainContent = document.getElementById('mainContent');
const navigationLinks = document.getElementById('navigationLinks');
const logoText = document.getElementById('LogoText');
const fullUser = document.getElementById('fullUser');
const collapsedUser = document.getElementById('collapsedUser');

let isSidebarOpen = true;

function toggleSidebar() {
    isSidebarOpen = !isSidebarOpen;

    if (window.innerWidth >= 1024) {
        // Desktop behavior
        if (isSidebarOpen) {
            sidebar.classList.remove('lg:w-20');
            sidebar.classList.add('lg:w-64');
            toggleIcon.style.transform = 'rotate(0deg)';
            mainContent.classList.remove('lg:ml-20');
            mainContent.classList.add('lg:ml-64');
            navigationLinks.querySelectorAll('span').forEach(span => span.classList.remove('hidden'));
            navigationLinks.querySelectorAll('i').forEach(i => i.classList.add('mr-3'));
            navigationLinks.querySelectorAll('a').forEach(a => a.classList.remove('mx-auto', 'w-min'));
            logoText.classList.remove('hidden');
            fullUser.classList.remove('hidden');
            collapsedUser.classList.add('hidden');
        } else {
            sidebar.classList.remove('lg:w-64');
            sidebar.classList.add('lg:w-20');
            toggleIcon.style.transform = 'rotate(180deg)';
            mainContent.classList.remove('lg:ml-64');
            mainContent.classList.add('lg:ml-20');
            document.querySelectorAll('#navigationLinks span').forEach(span => span.classList.add('hidden'));
            document.querySelectorAll('#navigationLinks i').forEach(i => i.classList.remove('mr-3'));
            document.querySelectorAll('#navigationLinks a').forEach(a => a.classList.add('mx-auto', 'w-min'));
            logoText.classList.add('hidden');
            fullUser.classList.add('hidden');
            collapsedUser.classList.remove('hidden');
        }
    } else {
        // Mobile behavior
        sidebar.classList.toggle('-translate-x-full');
    }
}

// Mobile menu button
mobileMenuButton.addEventListener('click', toggleSidebar);

// Desktop toggle button
sidebarToggle.addEventListener('click', toggleSidebar);

// Close sidebar when clicking outside on mobile
document.addEventListener('click', (event) => {
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickOnMenuButton = event.target === mobileMenuButton ||
        mobileMenuButton.contains(event.target);

    if (!isClickInsideSidebar && !isClickOnMenuButton && window.innerWidth < 1024 && !isSidebarOpen) {
        toggleSidebar();
    }
});