const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    watchOptions: {
        ignored: /node_modules/
    }
};
