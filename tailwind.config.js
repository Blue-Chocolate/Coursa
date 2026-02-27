export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.css',
        './app/**/*.php',          // ← add this
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php', // optional
    ],
    theme: { extend: {} },
    plugins: [],
}