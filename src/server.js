/*eslint-disable no-console, no-var */
var express = require('express')
var rewrite = require('express-urlrewrite')
var webpack = require('webpack')
var webpackDevMiddleware = require('webpack-dev-middleware')
var WebpackConfig = require('./webpack.server.config')

var app = express()

app.use(webpackDevMiddleware(webpack(WebpackConfig), {
  publicPath: '/assets/',
  stats: {
    colors: true
  }
}));

app.use('/', function ( req, res, next ) {
    // uri has a forward slash followed any number of any characters except full stops (up until the end of the string)
    if (/\/.*$/.test(req.url)) {
        res.sendFile(__dirname + '/resources/assets/index.html');
    } else {
        next();
    }
});

app.use(express.static(__dirname+'/resources/assets/'));

app.listen(8080, function () {
  console.log('Server listening on http://localhost:8080, Ctrl+C to stop')
});
