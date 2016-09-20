var fs = require('fs')
var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {

  entry: {
  	ruf:__dirname+'/resources/assets/js/src/index.js',
    vendor: ['react','redux','react-redux'],
  },

  output: {
    path: __dirname + '/../../../../public/assets',
    filename: '[name]-[chunkhash].js',
    chunkFilename: '[name]-[chunkhash].js',
    publicPath: '/assets/'
  },

  module: {
    loaders: [
     {
        test: /.jsx?$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets:[ 'es2015', 'react', 'stage-2' ]
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
        test: /\.png$/, 
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
        test: /\.woff2(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/font-woff"
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
      }
    ]
  },

  plugins: [
    new webpack.optimize.CommonsChunkPlugin('vendor','vendor-[chunkhash].js'),
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
    new ExtractTextPlugin("[name]-[chunkhash].css"),
    new HtmlWebpackPlugin({
    	title: 'Play Orange',
    	appMountId:'appRoot',
    	mobile:true,
    	template: __dirname+'/resources/assets/template.html', // Load a custom template (ejs by default see the FAQ for details)
    }),
    new webpack.optimize.OccurrenceOrderPlugin(true),

  ],

  recordsPath: path.join(__dirname, "../../../../webpack.records.json")

}
