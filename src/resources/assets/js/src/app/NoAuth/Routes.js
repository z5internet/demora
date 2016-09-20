'use strict';

var _config = require('../../../../../../../../../../resources/assets/react-app/config');

var _config2 = _interopRequireDefault(_config);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var routes = {
	childRoutes: [require('./Login'), require('./Logout'), require('./ForgottenPassword'), require('./Legal'), require('./Signup'), require('./Contact'), require('./Setup'), require('../../../../../../../../../../resources/assets/react-app/routes/openRoutes')]
};

if (_config2.default && _config2.default.addons) {

	for (var i = 0; i < _config2.default.addons.length; i++) {

		routes.childRoutes.push(require('../../../../../../../../' + _config2.default.addons[i] + '/src/resources/assets/js/src/app/NoAuth/Routes'));
	}
}

module.exports = routes;