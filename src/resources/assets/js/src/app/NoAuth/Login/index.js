'use strict';

var _Auth = require('../../../utils/Auth.js');

var _Auth2 = _interopRequireDefault(_Auth);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function redirectToDashboard(nextState, replace) {
	if (new _Auth2.default().loggedIn()) {
		replace('/');
	}
}

module.exports = {
	onEnter: redirectToDashboard,
	path: 'login',
	getComponent: function getComponent(nextState, cb) {
		require.ensure([], function (require) {
			cb(null, require('./components/Login'));
		});
	}
};