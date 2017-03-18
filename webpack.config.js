const webpack = require('webpack');

// 環境を記述 development or production
const env = 'development';

const config = {
  entry: './public/assets/js/react/entry.jsx',
  output: {
    path: 'public/assets/js/react',
    filename: 'bundle.js',
  },
  module: {
    loaders: [
      {
        test: /\.jsx$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets: ['react', 'es2015']
        }
      }
    ]
  },
  resolve: {
    extensions: ['*', '.js', '.jsx']
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
