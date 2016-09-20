'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _redexConnect = require('../../../../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

var _Http = require('../../../../utils/Http');

var _Http2 = _interopRequireDefault(_Http);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Security = function (_React$Component) {
	_inherits(Security, _React$Component);

	function Security(props, context) {
		_classCallCheck(this, Security);

		var _this = _possibleConstructorReturn(this, (Security.__proto__ || Object.getPrototypeOf(Security)).call(this, props, context));

		_this.state = {

			user: _this.props.user,
			error: '',
			sending: false

		};

		_this.store = _this.context.store;

		return _this;
	}

	_createClass(Security, [{
		key: 'saveSecurity',
		value: function saveSecurity() {
			var _this2 = this;

			var k = ['current_password', 'new_password', 'confirm_password'];

			var error = false;

			var data = {};

			this.setState({
				error: ''
			});

			for (var i = 0; i < k.length; i++) {

				data[k[i]] = this.refs[k[i]].value;
			}

			if (data.new_password != data.confirm_password) {

				this.setState({
					error: 'Your password doesn\'t match in the new and confirm boxes.'
				});

				error = true;
			}

			if (!data.confirm_password) {

				this.setState({
					error: 'You need to confirm your new password by typing in the box'
				});

				error = true;
			}

			if (!data.new_password) {

				this.setState({
					error: 'You need to enter a new password'
				});

				error = true;
			}

			if (!data.current_password) {

				this.setState({
					error: 'You need to enter your current password'
				});

				error = true;
			}

			if (!error) {

				this.setState({
					sending: 1
				});

				_Http2.default.put('/data/settings', {

					settings: data

				}).then(function (data) {

					_this2.setState({
						sending: 0
					});

					var error = '';

					if (!data.pwError) {

						_this2.store.dispatch({

							type: 'STORE_USER',
							user: data.user

						});

						error = 'Your password has been changed.';
					} else {

						error = data.pwError;
					}

					_this2.setState({
						error: error
					});
				});
			}
		}
	}, {
		key: 'render',
		value: function render() {

			return _react2.default.createElement(
				'div',
				null,
				!this.state.error ? '' : _react2.default.createElement(
					'div',
					{ className: 'text-xs-center alert alert-danger' },
					this.state.error
				),
				_react2.default.createElement(
					'div',
					{ className: 'row form-group' },
					_react2.default.createElement(
						'label',
						{ className: 'col-md-4 col-form-label' },
						'Current password'
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement('input', { ref: 'current_password', disabled: this.state.sending, type: 'password', className: 'form-control', placeholder: 'Current password' })
					)
				),
				_react2.default.createElement(
					'div',
					{ className: 'row form-group' },
					_react2.default.createElement(
						'label',
						{ className: 'col-md-4 col-form-label' },
						'New password'
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement('input', { ref: 'new_password', disabled: this.state.sending, type: 'password', className: 'form-control', placeholder: 'New password' })
					)
				),
				_react2.default.createElement(
					'div',
					{ className: 'row form-group' },
					_react2.default.createElement(
						'label',
						{ className: 'col-md-4 col-form-label' },
						'Confirm password'
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement('input', { ref: 'confirm_password', disabled: this.state.sending, type: 'password', className: 'form-control', placeholder: 'Confirm password' })
					)
				),
				_react2.default.createElement(
					'div',
					{ className: 'row form-group' },
					_react2.default.createElement('label', { className: 'col-md-4 col-form-label' }),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement(
							'button',
							{ className: 'btn btn-primary', disabled: this.state.sending, onClick: this.saveSecurity.bind(this) },
							'Change password'
						)
					)
				)
			);
		}
	}]);

	return Security;
}(_react2.default.Component);

Security.contextTypes = { store: _react2.default.PropTypes.object };

module.exports = Security;