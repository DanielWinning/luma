const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');

module.exports = {
    entry: {
        app: './assets/app.ts',
        styles: './assets/styles/app.scss'
    },
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
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
        }),
        new IgnoreEmitPlugin(['styles.js'])
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