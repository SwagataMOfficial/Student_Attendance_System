const theme_btn = document.querySelector('.right__themeToggle');

function addLightMode() {
    // this function sets the color scheme of the website into light mode colors
    theme_btn.innerHTML = '<span class="material-symbols-outlined">light_mode</span>';
    document.querySelector(':root').style.setProperty('--clr-nav', '#f0f0f0');
    document.querySelector(':root').style.setProperty('--clr-btn-text-logo', '#0b1b4c');
    document.querySelector(':root').style.setProperty('--clr-heading-mark', '#6e6e6e');
    document.querySelector(':root').style.setProperty('--clr-hover-color', '#cecece');
    document.querySelector(':root').style.setProperty('--clr-hover-p-color', 'black');
    document.querySelector(':root').style.setProperty('--clr-bgcolor', 'white');
    document.querySelector(':root').style.setProperty('--clr-theme-bg', 'darkorange');
    document.querySelector(':root').style.setProperty('--clr-essential-btn', '#a8a8a8');
    scheme = "light1";
    generateChart();
}

function addDarkMode() {
    // this function sets the color scheme of the website into dark mode colors
    theme_btn.innerHTML = '<span class="material-symbols-outlined">dark_mode</span>';
    document.querySelector(':root').style.setProperty('--clr-nav', '#141824');
    document.querySelector(':root').style.setProperty('--clr-btn-text-logo', '#9fa6bc');
    document.querySelector(':root').style.setProperty('--clr-heading-mark', '#6e7891');
    document.querySelector(':root').style.setProperty('--clr-hover-color', '#31374a');
    document.querySelector(':root').style.setProperty('--clr-hover-p-color', 'white');
    document.querySelector(':root').style.setProperty('--clr-bgcolor', '#0f111a');
    document.querySelector(':root').style.setProperty('--clr-theme-bg', '#131386');
    document.querySelector(':root').style.setProperty('--clr-essential-btn', '#1e2436');
    scheme = "dark1";
    generateChart();
}

// automatic theme setting handler
window.onload = () => {
    if (localStorage.getItem('theme') === 'dark') {
        addDarkMode();
    }
    else {
        addLightMode();
    }
}

// manually theme toggling handler
theme_btn.addEventListener('click', () => {
    if (theme_btn.innerText === "dark_mode") {
        // light mode designing
        addLightMode();
        localStorage.setItem('theme', 'light');
    } else {
        // dark mode designing
        addDarkMode();
        localStorage.setItem('theme', 'dark');
    }
});