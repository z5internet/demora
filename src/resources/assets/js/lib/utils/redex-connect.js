'use strict';

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactRedux = require('react-redux');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var redex_connect = function redex_connect(Component, stores) {

	Component.contextTypes = { store: _react2.default.PropTypes.object };

	function mapStateToProps(state) {

		var st = {};

		for (var i in stores) {

			st[i] = state[stores[i]];
		}

		return st;
	}

	function mapDispatchToProps(dispatch) {

		return {};
	}

	function mergeProps(stateProps, dispatchProps, ownProps) {

		return Object.assign({}, stateProps, ownProps);
	}

	return (0, _reactRedux.connect)(mapStateToProps, mapDispatchToProps, mergeProps)(Component);
};

module.exports = redex_connect;