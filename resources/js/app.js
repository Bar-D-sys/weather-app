import '../css/app.css';
import 'bootstrap';
// Check saved theme
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
}

document.addEventListener("DOMContentLoaded", () => {

    const button = document.getElementById("themeToggle");

    if (!button) return;

    // Update button text on page load
    if (document.body.classList.contains("dark-mode")) {
        button.textContent = "☀️ Light Mode";
    }

    button.addEventListener("click", () => {

        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {

            localStorage.setItem("theme", "dark");
            button.textContent = "☀️ Light Mode";

        } else {

            localStorage.setItem("theme", "light");
            button.textContent = "🌙 Dark Mode";

        }

    });

});