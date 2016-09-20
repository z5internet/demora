'use strict';

module.exports = {
	path: 'setup',
	getComponent: function getComponent(nextState, cb) {
		require.ensure([], function (require) {
			cb(null, require('./components/Setup'));
		});
	}
};