'use strict';

var _Auth = require('../../utils/Auth.js');

var _Auth2 = _interopRequireDefault(_Auth);

var _config = require('../../../../../../../../../../resources/assets/react-app/config');

var _config2 = _interopRequireDefault(_config);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var routes = {
	onEnter: new _Auth2.default().redirectToLogin,
	childRoutes: [require('./Settings'), require('./AuthHome'), require('./GetStarted'), require('../../../../../../../../../../resources/assets/react-app/routes/authRoutes')]
};

if (_config2.default && _config2.default.addons) {

	for (var i = 0; i < _config2.default.addons.length; i++) {

		var pkg = '../../../../../../../../' + _config2.default.addons[i] + '/src/resources/assets/js/src/app/Auth/Routes';

		routes.childRoutes.push(require('../../../../../../../../' + _config2.default.addons[i] + '/src/resources/assets/js/src/app/Auth/Routes'));
	}
}

module.exports = routes;