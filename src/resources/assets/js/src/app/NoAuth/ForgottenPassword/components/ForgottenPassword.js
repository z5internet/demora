'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _Auth = require('../../../../utils/Auth');

var _Auth2 = _interopRequireDefault(_Auth);

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ForgottenPassword = function (_Component) {
	_inherits(ForgottenPassword, _Component);

	function ForgottenPassword(props) {
		_classCallCheck(this, ForgottenPassword);

		var _this = _possibleConstructorReturn(this, (ForgottenPassword.__proto__ || Object.getPrototypeOf(ForgottenPassword)).call(this, props));

		_this.state = {
			error: false
		};
		return _this;
	}

	_createClass(ForgottenPassword, [{
		key: 'handlePassword',
		value: function handlePassword(event) {
			var _this2 = this;

			event.preventDefault();

			var email = this.refs.email.value;

			if (!email) return this.setState({ error: 'Please check your email address it doesn\'t look right.' });

			_Auth2.default.resetPassword(email, function (passwordSent) {

				if (!passwordSent) return _this2.setState({ error: 'There was a problem sending your email, please check and try again.' });

				return _this2.setState({ sent: 'An email has been sent to you. Follow the instructions to reset your password.' });
			});
		}
	}, {
		key: 'render',
		value: function render() {
			return _react2.default.createElement(
				'div',
				{ className: 'container' },
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8 offset-md-2' },
						_react2.default.createElement(
							'div',
							{ className: 'card' },
							_react2.default.createElement(
								'div',
								{ className: 'card-header' },
								'Reset password'
							),
							_react2.default.createElement(
								'div',
								{ className: 'card-block' },
								_react2.default.createElement(
									'div',
									{ className: 'card-text' },
									this.state.sent ? _react2.default.createElement(
										'div',
										{ className: 'alert alert-success' },
										this.state.sent
									) : _react2.default.createElement(
										'form',
										{ role: 'form' },
										_react2.default.createElement(
											'div',
											{ className: (0, _classnames2.default)('form-group', 'row', this.state.error ? 'has-danger' : '') },
											_react2.default.createElement(
												'label',
												{ className: 'col-md-4 col-form-label' },
												'E-Mail Address'
											),
											_react2.default.createElement(
												'div',
												{ className: 'col-md-6' },
												_react2.default.createElement('input', { ref: 'email', type: 'email', className: 'form-control', name: 'email' }),
												_react2.default.createElement(
													'span',
													{ className: 'form-text' },
													!this.state.error ? '' : this.state.error
												)
											)
										),
										_react2.default.createElement(
											'div',
											{ className: 'form-group row' },
											_react2.default.createElement(
												'div',
												{ className: 'col-md-6 offset-md-4' },
												_react2.default.createElement(
													'button',
													{ type: 'submit', className: 'btn btn-primary', onClick: this.handlePassword.bind(this) },
													_react2.default.createElement('i', { className: 'fa fa-btn fa-envelope' }),
													' Send Password Reset Link'
												)
											)
										)
									)
								)
							)
						)
					)
				)
			);
		}
	}]);

	return ForgottenPassword;
}(_react.Component);

module.exports = ForgottenPassword;