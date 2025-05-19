document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarTextElements = document.querySelectorAll('#sidebar span');
    const menuToggle = document.getElementById('menu-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');
        sidebarTextElements.forEach((element) => {
            element.classList.toggle('hidden', sidebar.classList.contains('w-20'));
        });
    }

    function expandSidebar() {
        if (sidebar.classList.contains('w-20')) {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            sidebarTextElements.forEach((element) => element.classList.remove('hidden'));
        }
    }

    function collapseSidebar() {
        if (sidebar.classList.contains('w-64')) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            sidebarTextElements.forEach((element) => element.classList.add('hidden'));
        }
    }

    menuToggle.addEventListener('click', toggleSidebar);

    document.querySelectorAll('#sidebar a').forEach((item) => {
        item.addEventListener('click', expandSidebar);
    });

    document.addEventListener('click', (event) => {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            collapseSidebar();
        }
    });
});
