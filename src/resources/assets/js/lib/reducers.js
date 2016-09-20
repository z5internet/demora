'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _redux = require('redux');

var _config = require('../../../../../../../../resources/assets/react-app/config');

var _config2 = _interopRequireDefault(_config);

var _reducers = require('../../../../../../../../resources/assets/react-app/reducers');

var _reducers2 = _interopRequireDefault(_reducers);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var combined = {};

if (_config2.default && _config2.default.addons) {

	for (var i = 0; i < _config2.default.addons.length; i++) {

		var reduc = require('../../../../../../' + _config2.default.addons[i] + '/src/resources/assets/js/src/reducers');

		for (var ii in reduc) {

			combined[ii] = reduc[ii];
		}
	}
}

if (_reducers2.default) {

	for (var _ii in _reducers2.default) {

		combined[_ii] = _reducers2.default[_ii];
	}
}

var user = function user() {
	var state = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];
	var action = arguments[1];


	switch (action.type) {

		case 'STORE_USER':

			return Object.assign({}, action.user);

		default:

			return state;

	}
};

var router = function router() {
	var state = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];
	var action = arguments[1];


	switch (action.type) {

		case 'ROUTER_CHANGE':

			if (!state.router) {
				state.router = 0;
			}

			state.router++;

			return Object.assign({}, state);

		default:

			return state;

	}
};

var website = function website() {
	var state = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];
	var action = arguments[1];


	switch (action.type) {

		case 'STORE_WEBSITE':

			return Object.assign({}, action.website);

		default:

			return state;

	}
};

var toastr = function toastr() {
	var state = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];
	var action = arguments[1];


	switch (action.type) {

		case 'TOASTR':

			return action.message.slice(0);

		default:

			return state;

	}
};

combined.user = user;
combined.website = website;
combined.router = router;
combined.toastr = toastr;

var reducers = (0, _redux.combineReducers)(combined);

exports.default = reducers;