import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';


export default defineConfig(({command, mode}) => {
    return {
        plugins: [
            laravel({
                manifest: true,
                hotFile: 'storage/admin.hot', // Customize the "hot" file...
                buildDirectory: "assets", // Customize the build directory...
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
    };

    // Default return
    return {};
});
