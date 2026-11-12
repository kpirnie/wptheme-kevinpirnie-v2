const esbuild = require('esbuild');
const isWatch = process.argv.includes('--watch');
const config = {
    entryPoints: ['./assets/js/main.js'],
    bundle: true,
    minify: !isWatch,
    treeShaking: true,
    outfile: './assets/js/theme.min.js',
    format: 'iife',
    platform: 'browser',
    target: ['es2020'],
    logLevel: 'info',
    pure: ['console.log', 'console.debug'],
};
async function build() {
    if (isWatch) {
        const context = await esbuild.context(config);
        await context.watch();
    } else {
        await esbuild.build(config);
    }
}
build().catch(() => process.exit(1));
