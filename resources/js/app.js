import './bootstrap';

const THEME_KEY = 'zoltare-theme';
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

function setTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem(THEME_KEY, 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem(THEME_KEY, 'light');
    }
}

function initTheme() {
    const stored = localStorage.getItem(THEME_KEY);
    if (stored) {
        setTheme(stored);
    } else if (prefersDark.matches) {
        setTheme('dark');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initTheme();

    const toggle = document.getElementById('theme-toggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        });
    }
});
