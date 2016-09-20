'use strict';

module.exports = {
	childRoutes: [{
		path: 'settings',
		getComponent: function getComponent(nextState, cb) {
			require.ensure([], function (require) {
				cb(null, require('./components/Settings'));
			}, 'auth/settings');
		}
	}, {
		path: 'settings/profile',
		getComponent: function getComponent(nextState, cb) {
			require.ensure([], function (require) {
				cb(null, require('./components/Settings'));
			}, 'auth/settings');
		}
	}, {
		path: 'settings/security',
		getComponent: function getComponent(nextState, cb) {
			require.ensure([], function (require) {
				cb(null, require('./components/Settings'));
			}, 'auth/settings');
		}
	}, {
		path: 'settings/changeEmail',
		getComponent: function getComponent(nextState, cb) {
			require.ensure([], function (require) {
				cb(null, require('./components/Settings'));
			}, 'auth/settings');
		}
	}]
};