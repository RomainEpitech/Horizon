document.addEventListener("DOMContentLoaded", function() {
    const themeTogglerDark = document.getElementById('toggle-dark');
    const themeTogglerDefault = document.getElementById('toggle-default');
    const main = document.querySelector('html');

    const savedTheme = localStorage.getItem('theme');
    if(savedTheme) {
        main.setAttribute('data-bs-theme', savedTheme);
        if(savedTheme === 'dark') {
            themeTogglerDark.style.display = 'none';
            themeTogglerDefault.style.display = '';
        } else {
            themeTogglerDefault.style.display = 'none';
            themeTogglerDark.style.display = '';
        }
    }

    themeTogglerDark.addEventListener("click", function() {
        main.setAttribute('data-bs-theme', 'dark');
        themeTogglerDark.style.display = 'none';
        themeTogglerDefault.style.display = '';
        localStorage.setItem('theme', 'dark');
    })

    themeTogglerDefault.addEventListener("click", function() {
        main.setAttribute('data-bs-theme', 'light');
        themeTogglerDefault.style.display = 'none';
        themeTogglerDark.style.display = '';
        localStorage.setItem('theme', 'light');
    })
})