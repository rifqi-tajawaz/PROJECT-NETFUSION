const fs = require('fs');

try {
    const packageJson = JSON.parse(fs.readFileSync('package.json', 'utf8'));
    const requiredDevDeps = ['tailwindcss', 'postcss', 'autoprefixer', 'vite', 'laravel-vite-plugin'];
    const missing = requiredDevDeps.filter(dep => !packageJson.devDependencies[dep]);

    if (missing.length > 0) {
        console.error('Missing devDependencies:', missing);
        process.exit(1);
    }

    if (!fs.existsSync('tailwind.config.js')) {
        console.error('tailwind.config.js missing');
        process.exit(1);
    }

    if (!fs.existsSync('postcss.config.js')) {
        console.error('postcss.config.js missing');
        process.exit(1);
    }

    console.log('Build configuration looks correct.');
} catch (e) {
    console.error('Error verifying build config:', e.message);
    process.exit(1);
}
