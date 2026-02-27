import './bootstrap';

// Don't import or start Alpine yourself — Livewire owns it.
// Just register your components before Livewire boots Alpine.

document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        isDark: document.documentElement.classList.contains('dark'),

        init() {
            console.log('darkMode init, isDark:', this.isDark);
        },

        toggle() {
            console.log('toggle called');
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
    }));
});