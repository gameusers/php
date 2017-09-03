const webpack = require('webpack');
const path = require('path');

// 環境を記述 development or production
const env = 'development';

const config = {
  entry: {
    root: ['babel-polyfill', './public/react/js/entry-root.jsx']
  },
  output: {
    path: path.join(__dirname, '/public/react/js'),
    filename: '[name]-bundle.min.js',
  },
  module: {
    rules: [
      {
        test: /\.jsx$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
        options: {
          presets: ['react', 'es2015', 'es2016', 'es2017']
        }
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          { loader: 'css-loader', options: { importLoaders: 1 } },
          'postcss-loader'
        ]
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx', 'css']
  },
  plugins: [
    new webpack.EnvironmentPlugin({
      NODE_ENV: env
    }),
  ],
  devtool: 'source-map'
};

// Production ビルドの場合は圧縮する
if (env === 'production') {
  config.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false
      }
    })
  );
}

module.exports = config;
