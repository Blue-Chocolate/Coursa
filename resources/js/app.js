import './bootstrap';
import './lesson-player';

document.addEventListener('alpine:init', () => {

    // ── Component #5: Dark mode toggle ────────────────────────────────────
    Alpine.data('darkMode', () => ({
        isDark: document.documentElement.classList.contains('dark'),

        init() {
            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme')) {
                        this.isDark = e.matches;
                        document.documentElement.classList.toggle('dark', this.isDark);
                    }
                });
        },

        toggle() {
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
    }));

    // ── Notification bell ─────────────────────────────────────────────────
    Alpine.data('notifications', () => ({
        open:        false,
        items:       [],
        unreadCount: 0,
        loading:     false,

        init() {
            this.fetch();
            setInterval(() => this.fetch(), 60_000);
        },

        async fetch() {
            this.loading = true;
            try {
                const res  = await fetch('/notifications', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const json = await res.json();
                this.items       = json.notifications;
                this.unreadCount = json.unread_count;
            } catch (e) {
                // silently fail — don't break the page
            } finally {
                this.loading = false;
            }
        },

        toggle() {
            this.open = !this.open;
            if (this.open && this.unreadCount > 0) {
                this.markAllRead();
            }
        },

        async markAllRead() {
            await fetch('/notifications/read-all', {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN':      document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With':  'XMLHttpRequest',
                },
            });
            this.items       = this.items.map(n => ({ ...n, read_at: new Date().toISOString() }));
            this.unreadCount = 0;
        },

        async markRead(id) {
            await fetch(`/notifications/${id}/read`, {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            this.items       = this.items.map(n => n.id === id ? { ...n, read_at: new Date().toISOString() } : n);
            this.unreadCount = Math.max(0, this.unreadCount - 1);
        },

        timeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (diff < 60)    return `${diff}s ago`;
            if (diff < 3600)  return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            return `${Math.floor(diff / 86400)}d ago`;
        },
    }));

});