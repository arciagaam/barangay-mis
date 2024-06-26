import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js', 'resources/js/calendar.js', 'resources/js/lend.js', 'resources/js/mapping.js', 'resources/js/nav.js', 'resources/js/residents.js', 'resources/js/searchitem.js', 'resources/js/searchresident.js', 'resources/js/settings.js', 'resources/js/popup.js', 'resources/js/barangay_information.js', 'resources/js/age.js'],
            refresh: true,
        }),
    ],
});
