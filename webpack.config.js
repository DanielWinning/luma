const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');

const entries = {
    app: './assets/app.ts',
    styles: './assets/styles/app.scss',
    security: './assets/styles/security/security.scss'
}
const ignoreFiles = Object.keys(entries).reduce((acc, key) => {
    if (entries[key].endsWith('.scss')) {
        acc.push(`${key}.js`);
    }

    return acc;
}, []);

module.exports = {
    entry: entries,
    module: {
        rules: [
            {
                test: /\.ts?$/,
                use: 'ts-loader',
                exclude: /node_modules/
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ],
                exclude: /node_modules/
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
        }),
        new IgnoreEmitPlugin(ignoreFiles)
    ],
    resolve: {
        extensions: ['.ts', '.js', '.css', '.scss']
    },
    watchOptions: {
        poll: true,
        ignored: /node_modules/
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/assets/')
    }
}