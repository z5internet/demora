'use strict';

module.exports = {
	childRoutes: [{
		path: '/',
		component: require('../components/App.js'),
		childRoutes: [require('./NoAuth/Routes'), require('./Auth/Routes')]
	}]
};