import './bootstrap';
import './lesson-player';

document.addEventListener('alpine:init', () => {
    Alpine.data('lessonPlayer', (isCompleted, watchSeconds, videoUrl) => ({
        completed: isCompleted,
        courseProgress: 0,
        videoUrl,
        player: null,

        initPlayer() {
            const el = this.$refs.player.querySelector('div, video');
            if (!el) return;

            this.player = new Plyr(el, {
                controls: [
                    'play-large', 'play', 'progress',
                    'current-time', 'mute', 'volume',
                    'captions', 'fullscreen'
                ],
            });

            // Resume from saved position
            this.player.on('ready', () => {
                if (watchSeconds > 0) {
                    this.player.currentTime = watchSeconds;
                }
            });

            // Save watch progress every 5 seconds
            this.player.on('timeupdate', () => {
                const seconds = Math.floor(this.player.currentTime);
                if (seconds > 0 && seconds % 5 === 0) {
                    this.$wire.updateWatchSeconds(seconds);
                }
            });
        },

        onCourseCompleted(percentage) {
            this.courseProgress = percentage;
        },
    }));
});