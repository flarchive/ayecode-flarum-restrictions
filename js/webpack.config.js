// js/webpack.config.js
'use strict';

const path = require('path');

module.exports = {
    mode: 'production', // or 'development'
    entry: {
        forum: path.resolve(__dirname, 'src/forum/index.js'),
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].js',
        // Here’s the key: produce a CommonJS2 export
        libraryTarget: 'commonjs2',
    },
};
