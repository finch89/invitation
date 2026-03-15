// Gestion du thème (clair/sombre)
document.addEventListener('DOMContentLoaded', function() {
    const themeSwitch = document.getElementById('theme-switch');
    const htmlElement = document.documentElement;
    
    // Vérifier le thème sauvegardé
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', savedTheme);
    updateThemeButton(savedTheme);
    
    // Changer de thème au clic
    if (themeSwitch) {
        themeSwitch.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);
        });
    }
    
    function updateThemeButton(theme) {
        if (themeSwitch) {
            themeSwitch.textContent = theme === 'light' ? '🌙' : '☀️';
            themeSwitch.setAttribute('aria-label', theme === 'light' ? 'Passer au thème sombre' : 'Passer au thème clair');
        }
    }
});