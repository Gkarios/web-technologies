function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
}

function getCookie(name) {
    return document.cookie.split('; ').reduce((r, v) => {
        const parts = v.split('=')
        return parts[0] === name ? decodeURIComponent(parts[1]) : r;
    }, '');
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', newTheme);
    setCookie('theme', newTheme, 30); // Store in cookie for 30 days
}

function applyTheme() {
    const savedTheme = getCookie('theme') || 'light'; // Default to light if no cookie
    document.documentElement.setAttribute('data-theme', savedTheme);
}

window.onload = applyTheme;