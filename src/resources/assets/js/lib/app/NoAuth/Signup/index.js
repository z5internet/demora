'use strict';

module.exports = {
	path: 'signup',
	getComponent: function getComponent(nextState, cb) {
		require.ensure([], function (require) {
			cb(null, require('./components/Signup'));
		});
	}
};