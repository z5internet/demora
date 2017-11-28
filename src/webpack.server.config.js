var fs = require('fs')
var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {

  entry: {
    ruf:__dirname+'/resources/assets/js/src/index.js'
  },

  output: {
    path: __dirname + '/__build__/',
    filename: '[name].js',
    chunkFilename: '[id].js',
    publicPath: '/__build__/'
  },

  module: {
    loaders: [
     {
        test: /.jsx?$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets:[ 'es2015', 'react', 'stage-2' ],
        }
      },
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract("style-loader", "css-loader")
      },
      {
        test: /\.scss$/,
        loaders: ['style', 'css', 'sass']
      },
      {
        test: /\.png|\.gif$/,
        loader: "url-loader?limit=100000"
      },
      {
        test: /\.jpg$/,
        loader: "file-loader"
      },
      {
        test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/,
        loader: 'url?limit=10000&mimetype=application/font-woff'
      },
      {
        test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
        loader: 'url?limit=10000&mimetype=application/octet-stream'
      },
      {
        test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
        loader: 'file'
      },
      {
        test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
        loader: 'url?limit=10000&mimetype=image/svg+xml'
      },
      {
        test: /\.json$/,
        loader: 'json-loader'
      }
    ]
  },

  plugins: [
    new webpack.optimize.CommonsChunkPlugin('shared.js'),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }),
    (function() {
      return process.env.NODE_ENV=='production'?new webpack.optimize.UglifyJsPlugin({
        compress: {
            warnings: false
        }
      }):function(){};
    })(),
    new ExtractTextPlugin("[name].css"),
  ],

  devtool: process.env.NODE_ENV=='production'?null:'inline-source-map',

  resolve: {
    // add alias for application code directory
    alias:{
      rufUtils: path.resolve( __dirname, './resources/assets/js/src/utils' ),
      TagBlock: path.resolve( __dirname, '../../ruf-tag/src/resources/assets/js/src/components/TagBlock'),
      resources: path.resolve( __dirname, '../../../../resources'),
    },
    extensions: [ '', '.js' ]
  },

}
