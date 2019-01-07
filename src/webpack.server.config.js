var fs = require('fs')
var path = require('path')
var webpack = require('webpack')
var MiniCssExtractPlugin = require("mini-css-extract-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');
var CopyWebpackPlugin = require('copy-webpack-plugin');

const devMode = process.env.NODE_ENV !== 'production'

module.exports = {

  entry: {
    ruf: __dirname+'/resources/assets/js/src/index.js',
  },

  output: {
    path: __dirname + '/assets/',
    filename: '[name].js',
    chunkFilename: '[id].js',
    publicPath: '/assets/'
  },

  mode: 'development',

  module: {
    rules: [{
        test: /.jsx?$/,
        exclude: /(node_modules)/,
        loader: 'babel-loader',
        options: {
          presets: [
            [
              '@babel/preset-env', {
                modules: false
              }
            ],
            '@babel/react',
          ],
          'plugins': [
            '@babel/plugin-proposal-class-properties',
            '@babel/plugin-syntax-dynamic-import'
          ]
        }
      },
      {
          test: /\.s?[ac]ss$/,
          use: [
              MiniCssExtractPlugin.loader,
              {
                loader: 'css-loader',
                options: {
                  url: true,
                  sourceMap: true
                }
              },
              {
                loader: 'sass-loader',
                options: {
                  sourceMap: true
                }
              }
          ],
      },
      {
        test: /\.png|\.gif$/,
        use: [

          {

            loader: 'url-loader',
            options: {
              limit: 10000,

            }
          }

        ]
      },
      {
        test: /\.jpg$/,
        use: [

          {

            loader: 'url-loader'

          }

        ]
      },
      {

        test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        use: [

          {

            loader: 'url-loader',
            options: {
              limit: 10000,

            }
          }

        ]

      },
      {

        test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
        use: [

          {
              loader: 'file-loader',
              options: {
                  name: '[name]-[hash:6].[ext]'
              },
          }

        ]
      },
      {
        test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
        use: [

          {
              loader: 'file-loader',
              options: {
                  name: '[name]-[hash:6].[ext]'
              },
          }

        ]
      },
      {
        test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
        use: [

          {

            loader: 'url-loader',
            options: {
              limit: 10000,
              name: './svg-[hash].[ext]',
              mimetype: 'image/svg+xml'
            }

          }

        ]
      }

    ]

  },

  optimization: {
    splitChunks: {
      chunks: 'async',
      minSize: 30000,
      maxSize: 0,
      minChunks: 1,
      maxAsyncRequests: 5,
      maxInitialRequests: 3,
      automaticNameDelimiter: '~',
      name: 'shared',
      cacheGroups: {
        node_vendors: {
          test: /[\\/]node_modules[\\/]/,
          priority: -10
        },
        vendors: {
          test: /[\\/]vendor[\\/]/,
          priority: -10
        },
        default: {
          minChunks: 2,
          priority: -20,
          reuseExistingChunk: true
        }
      }
    }
  },

  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }),
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: "[name].css",
      chunkFilename: "[id].css"
    }),
    new CopyWebpackPlugin([
        {from: __dirname+'/resources/images', to: ''}
    ]),
  ],

  devtool: process.env.NODE_ENV=='production'?null:'inline-source-map',

  resolve: {
    // add alias for application code directory
    alias:{
      rufUtils: path.resolve( __dirname, './resources/assets/js/src/utils' ),
      TagBlock: path.resolve( __dirname, '../../ruf-tag/src/resources/assets/js/src/components/TagBlock'),
      resources: path.resolve( __dirname, '../../../../resources'),
    },
    extensions: [ '.js' ]
  },

}
