const glob = require('glob');
const path = require('path');
const webpack = require('webpack');
var srcDir = path.resolve(__dirname + '/../public/src/js/');
var distDir = path.resolve(__dirname + '/../public/dist/');

function entries() {
    var result = {};
    glob.sync(srcDir + '/*.js').forEach(file => {
        var key = path.basename(file, '.js');
        var val = file;
        result[key] = val;
    });
    return result;
}

module.exports = {
    entry: entries(),
    output: {
        path: distDir,
        filename: '[name].js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        }),
        /*new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        }),*/
    ],
    module: {
        rules: [{
            test: /\.css$/,
            exclude: /(node_modules)/,
            use: [
                'style-loader',
                'css-loader'
            ]
        }, {
            test: /\.(gif|jpg|png|woff|svg|eot|ttf)\??.*$/,
            loader: 'url-loader?limit=1024'
        }, {
            //jquery.js的路径
            test: require.resolve('../public/plugins/jQuery/jquery-2.2.3.min.js'),
            use: [{
                loader: 'expose-loader',
                options: 'jquery'
            }, {
                loader: 'expose-loader',
                options: 'jQuery'
            }, {
                loader: 'expose-loader',
                options: '$'
            }]
        }]
    },
    externals: {
        jquery: "jQuery" //如果要全局引用jQuery，不管你的jQuery有没有支持模块化，用externals就对了。
    }
}
