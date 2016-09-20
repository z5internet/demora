'use strict';

module.exports = {
	path: 'logout',
	getComponent: function getComponent(nextState, cb) {
		require.ensure([], function (require) {
			cb(null, require('./components/Logout'));
		});
	}
};