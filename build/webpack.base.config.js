const os = require('os');
const glob = require('glob');
const glob = require('glob');
const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

console.log(resolve('../public/src'));

function resolve(dir) {
    return path.join(__dirname, dir);
}

function entries() {
    var jsDir = path.resolve
}

module.exports = {
    entry: {
        main: '@/main',
        'vender-base': '@/vendors/vendors.base.js',
        'vender-exten': '@/vendors/vendors.exten.js'
    },
    output: {
        path: path.resolve(__dirname, '../public/dist')
    },
    module: {
        rules: [{

        }, ]
    },
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            'vue': 'vue/dist/vue.esm.js',
            '@': resolve('../public/src'),
        }
    }
};
