import Chart from 'chart.js/auto';
window.Chart = Chart;

document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("darkModeToggle");
    const icon = document.getElementById("darkIcon");
    const body = document.getElementById("main-body");
    const html = document.documentElement;

    if (localStorage.getItem("theme") === "dark") {
        enableDarkMode();
    }

    toggleBtn.addEventListener("click", () => {
        if (html.getAttribute("data-theme") === "light") {
            enableDarkMode();
            localStorage.setItem("theme", "dark");
        } else {
            disableDarkMode();
            localStorage.setItem("theme", "light");
        }
    });

    function enableDarkMode() {
        html.setAttribute("data-theme", "dark");
        body.classList.remove("bg-light", "text-dark");
        body.classList.add("bg-dark", "text-light");
        icon.classList.remove("bi-moon-stars");
        icon.classList.add("bi-sun");
    }

    function disableDarkMode() {
        html.setAttribute("data-theme", "light");
        body.classList.remove("bg-dark", "text-light");
        body.classList.add("bg-light", "text-dark");
        icon.classList.remove("bi-sun");
        icon.classList.add("bi-moon-stars");
    }
});
