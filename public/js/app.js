document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.getElementById('main-content');

    if (sidebar && mainContent) {
        const headerHeight = document.querySelector('header')?.offsetHeight || 0; // Get header height if it exists

        mainContent.addEventListener('scroll', function() {
            if (mainContent.scrollTop > headerHeight) {
                sidebar.classList.add('fixed-sidebar');
            } else {
                sidebar.classList.remove('fixed-sidebar');
            }
        });
    }
});
