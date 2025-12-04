import './bootstrap';

const THEME_KEY = 'zoltare-theme';
const supportsMatchMedia = typeof window !== 'undefined' && typeof window.matchMedia === 'function';
const prefersDark = supportsMatchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

const getStoredTheme = () => {
    try {
        return localStorage.getItem(THEME_KEY);
    } catch (error) {
        console.warn('Unable to read stored theme', error);
        return null;
    }
};

let currentTheme = null;

const applyThemeClass = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    currentTheme = theme;
};

const setTheme = (theme) => {
    applyThemeClass(theme);
    try {
        localStorage.setItem(THEME_KEY, theme);
    } catch (error) {
        console.warn('Unable to persist theme', error);
    }
};

const resolveTheme = () => getStoredTheme() || (prefersDark?.matches ? 'dark' : 'light');

const ensureThemeClass = () => {
    const desired = resolveTheme();
    const hasDark = document.documentElement.classList.contains('dark');
    if ((desired === 'dark' && !hasDark) || (desired === 'light' && hasDark)) {
        applyThemeClass(desired);
    } else {
        currentTheme = desired;
    }
};

const handleThemeToggleClick = (event) => {
    const toggle = event.target.closest('#theme-toggle');
    if (!toggle) {
        return;
    }

    const isDark = document.documentElement.classList.contains('dark');
    setTheme(isDark ? 'light' : 'dark');
};

ensureThemeClass();

let clickBound = false;
const bindThemeHandlers = () => {
    ensureThemeClass();
    if (clickBound) {
        return;
    }
    document.addEventListener('click', handleThemeToggleClick);
    clickBound = true;
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindThemeHandlers);
} else {
    bindThemeHandlers();
}

window.addEventListener('load', ensureThemeClass);
document.addEventListener('livewire:init', ensureThemeClass);
document.addEventListener('livewire:navigated', ensureThemeClass);

prefersDark?.addEventListener('change', () => {
    if (!getStoredTheme()) {
        ensureThemeClass();
    }
});

if (typeof MutationObserver === 'function') {
    const observer = new MutationObserver(() => ensureThemeClass());
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
}
