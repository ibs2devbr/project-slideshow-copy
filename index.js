import cssnano from 'cssnano';

import { readFileSync, writeFileSync } from 'fs';

async function setMinify () {
    try {
        const result = await cssnano.process (readFileSync ('./css/_style.css', 'utf8'), {
            from : './css/_style.css',
        });
        writeFileSync ('./css/minify.css', result.css);
    } catch (error) {
        console.error (error);
    };
};

setMinify ();