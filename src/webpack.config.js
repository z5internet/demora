var fs = require('fs')
var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');
var CopyWebpackPlugin = require('copy-webpack-plugin');

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
        test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: "url-loader?limit=10000&mimetype=application/font-woff"
      },
      {
        test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: "file-loader"
      },
      {
        test: /\.json$/,
        loader: 'json-loader'
      }
    ]
  },

  plugins: [
    new CopyWebpackPlugin([
      {
        from: __dirname+'/resources/images/',
        to: __dirname + '/../../../../public/assets/',
      },
    ]),
    new webpack.optimize.CommonsChunkPlugin('vendor','vendor-[chunkhash].js'),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }),
    (function() {
      return process.env.NODE_ENV=='production'?new webpack.optimize.UglifyJsPlugin({
        compress: {
            warnings: false,
        },
        sourceMap: true,
        mangle: true,
        beautify: false,
        comments: false,
      }):function(){};
    })(),
    new ExtractTextPlugin("[name]-[chunkhash].css"),
    new HtmlWebpackPlugin({
    	appMountId:'appRoot',
    	mobile:true,
      title: rufConfig.title,
    	template: __dirname+'/resources/assets/template.html', // Load a custom template (ejs by default see the FAQ for details)
    }),
    new webpack.optimize.OccurrenceOrderPlugin(true),
    new webpack.SourceMapDevToolPlugin({
      filename: '[name]-[chunkhash].js.map',
//      exclude: ['vendor.js']
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
    extensions: [ '', '.js' ]
  },

}
