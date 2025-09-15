// A more elegant Node.js script to create a production-ready zip file.

const fs = require('fs');
const archiver = require('archiver');
const path = require('path');

// --- Configuration ---
const PLUGIN_NAME = 'sc-events';

// Extract version from the main plugin file
function getPluginVersion() {
    try {
        const pluginContent = fs.readFileSync(`${PLUGIN_NAME}.php`, 'utf8');
        const versionMatch = pluginContent.match(/Version:\s*([\d.]+)/);
        return versionMatch ? versionMatch[1] : '1.0.0';
    } catch (error) {
        console.warn('Could not read version from plugin file, using 1.0.0');
        return '1.0.0';
    }
}

const VERSION = getPluginVersion();
// THE FIX: Save the zip file in the parent directory with version number.
const ZIP_PATH = `../${PLUGIN_NAME}-v${VERSION}.zip`; 

// Define all files and folders to be EXCLUDED.
const EXCLUDED_ITEMS = [
    '.git',
    '.gitignore',
    '.DS_Store',
    'node_modules',
    'assets/scss',
    'package.json',
    'package-lock.json',
    'build.js',
    'readme.md',
    'guide.txt',
    `${PLUGIN_NAME}*.zip`,
    'build' // Exclude the old build folder name just in case
];

async function build() {
    console.log(`Starting the build process for ${PLUGIN_NAME} v${VERSION}...`);

    try {
        // 1. Clean up the old zip file if it exists.
        if (fs.existsSync(ZIP_PATH)) {
            fs.unlinkSync(ZIP_PATH);
            console.log('-> Removed old zip file.');
        }

        // 2. Create the zip archive.
        console.log(`-> Creating new zip file at: ${path.resolve(ZIP_PATH)}`);
        const output = fs.createWriteStream(ZIP_PATH);
        const archive = archiver('zip', { zlib: { level: 9 } });

        archive.on('error', (err) => { throw err; });
        archive.pipe(output);

        // 3. Add files and directories to the archive, respecting the exclusion list.
        console.log('-> Adding files to the archive...');
        const files = fs.readdirSync('.');
        
        files.forEach(item => {
            // If the item is NOT in the exclusion list, add it to the zip.
            if (!EXCLUDED_ITEMS.includes(item)) {
                const stats = fs.statSync(item);
                if (stats.isDirectory()) {
                    console.log(`   - Adding directory: ${item}`);
                    archive.directory(item, `${PLUGIN_NAME}/${item}`);
                } else {
                    console.log(`   - Adding file: ${item}`);
                    archive.file(item, { name: `${PLUGIN_NAME}/${item}` });
                }
            } else {
                console.log(`   - Excluding: ${item}`);
            }
        });

        // Finalize the archive.
        await archive.finalize();

        console.log(`   Zip file created successfully. Total size: ${Math.round(archive.pointer() / 1024)} KB`);
        console.log('Build process finished.');

    } catch (error) {
        console.error('An error occurred during the build process:', error);
    }
}

// Run the build function
build();