import './bootstrap';
import './lesson-player';

document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        isDark: document.documentElement.classList.contains('dark'),

        init() {
            console.log('darkMode init, isDark:', this.isDark);
        },

        toggle() {
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
    }));
});