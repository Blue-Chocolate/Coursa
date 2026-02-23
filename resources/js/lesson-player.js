// Alpine.js component for the lesson player
// Handles: Plyr init (Feature #4), watch time tracking, completion state (Feature #2 & #3)

document.addEventListener('alpine:init', () => {
    Alpine.data('lessonPlayer', (isCompleted, watchSeconds, videoUrl) => ({
        completed:      isCompleted,
        courseProgress: 0,
        player:         null,
        saveInterval:   null,

        // ── Plyr Initialization (Alpine Feature #4) ───────────────────────
        initPlayer() {
            // x-ref="player" gives us the DOM element
            this.player = new Plyr(this.$refs.player.querySelector('[data-plyr-provider]'), {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                youtube:  { noCookie: true },
            });

            // Resume from saved position
            this.player.on('ready', () => {
                if (watchSeconds > 30) {
                    this.player.currentTime = watchSeconds;
                }
            });

            // Save watch time every 10 seconds
            this.saveInterval = setInterval(() => {
                if (this.player?.playing) {
                    const seconds = Math.floor(this.player.currentTime);
                    this.$wire.updateWatchSeconds(seconds);
                }
            }, 10000);
        },

        // ── Called when Livewire fires 'lesson-completed' event ───────────
        onCourseCompleted(percentage) {
            this.completed      = true;
            this.courseProgress = percentage;
        },

        // ── Cleanup on component destroy ──────────────────────────────────
        destroy() {
            if (this.saveInterval) clearInterval(this.saveInterval);
            if (this.player)       this.player.destroy();
        },
    }));
});