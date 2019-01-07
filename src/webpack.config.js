var fs = require('fs')
var path = require('path')
var webpack = require('webpack')
var MiniCssExtractPlugin = require("mini-css-extract-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');
var CopyWebpackPlugin = require('copy-webpack-plugin');
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const { ImageminWebpackPlugin } = require("imagemin-webpack");
const imageminGifsicle = require("imagemin-gifsicle");
const imageminManifest = {};

const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const devMode = process.env.NODE_ENV !== 'production'

var os = require('os')

var fs = require("fs");

var contents = fs.readFileSync(__dirname+'/../../../../storage/ruf.json');
var rufConfig = JSON.parse(contents);

module.exports = {

  entry: {
  	ruf:__dirname+'/resources/assets/js/src/index.js',
    vendor: ['react', 'redux', 'react-redux', 'prop-types', 'react-router-dom', 'react-dom', 'cookieconsent'],
  },

  output: {
    path: __dirname + '/../../../../public/assets',
    filename: '[name]-[chunkhash].js',
    chunkFilename: '[name]-[chunkhash].js',
    publicPath: '/assets/'
  },

  mode: 'production',

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
        use: 'url-loader?limit=10000'

      },
      {

        test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
        use: 'file-loader'

      },
      {
        test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
        use: [

          {

            loader: 'file-loader'

          }

        ]
      },      {
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
    minimizer: [
      new UglifyJsPlugin({
        cache: true,
        parallel: true,
        sourceMap: true // set to true if you want JS source maps
      }),
      new OptimizeCSSAssetsPlugin({
        cssProcessorPluginOptions: {
          preset: ['default', { discardComments: { removeAll: true } }],
        },
      })
    ],
    splitChunks: {
      chunks: 'all',
      minSize: 30000,
      maxSize: 0,
      minChunks: 2,
      maxAsyncRequests: 5,
      maxInitialRequests: 3,
      automaticNameDelimiter: '~',
      name: true,
      cacheGroups: {}
    }
  },

  plugins: [
    new CopyWebpackPlugin([
      {
        from: __dirname+'/resources/images/',
        to: __dirname + '/../../../../public/assets/',
      },
      {
        from: __dirname+'/../../../../resources/assets/public/',
        to: __dirname + '/../../../../public/assets/',
      },
    ]),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }),
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: "[name]-[chunkhash].css",
      chunkFilename: "[id]-[chunkhash].css"
    }),
    new HtmlWebpackPlugin({
    	appMountId:'appRoot',
    	mobile:true,
      title: rufConfig.title,
    	template: __dirname+'/resources/assets/template.html', // Load a custom template (ejs by default see the FAQ for details)
    }),
    new webpack.optimize.OccurrenceOrderPlugin(true),
    new webpack.SourceMapDevToolPlugin({
      filename: '[name]-[chunkhash].js.map',
    }),
  ],

  recordsPath: path.join(__dirname, "../../../../webpack.records.json"),

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
