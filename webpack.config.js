const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

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

module.exports = (env, options) => {
    const isProduction = options.mode === 'production';

    return {
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
                        {
                            loader: 'postcss-loader',
                            options: {
                                postcssOptions: {
                                    plugins: [
                                        require('tailwindcss'),
                                        require('autoprefixer')
                                    ]
                                }
                            }
                        },
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
        optimization: isProduction ? {
            minimize: true,
            minimizer: [
                new TerserPlugin(),
                new CssMinimizerPlugin(),
            ]
        } : {},
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
    };
}