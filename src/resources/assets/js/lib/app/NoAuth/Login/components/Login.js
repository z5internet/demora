'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactRouter = require('react-router');

var _Auth = require('../../../../utils/Auth.js');

var _Auth2 = _interopRequireDefault(_Auth);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Login = function (_Component) {
	_inherits(Login, _Component);

	function Login(props, context) {
		_classCallCheck(this, Login);

		var _this = _possibleConstructorReturn(this, (Login.__proto__ || Object.getPrototypeOf(Login)).call(this, props, context));

		_this.state = {
			error: false
		};

		_this.store = context.store;

		_this.auth = new _Auth2.default();

		return _this;
	}

	_createClass(Login, [{
		key: 'handleLogin',
		value: function handleLogin(event) {

			event.preventDefault();

			var email = this.refs.email.value;
			var password = this.refs.password.value;

			this.auth.login(email, password, this);
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
								'Login'
							),
							_react2.default.createElement(
								'div',
								{ className: 'card-block' },
								_react2.default.createElement(
									'div',
									{ className: 'card-text' },
									!this.state.error ? '' : _react2.default.createElement(
										'div',
										{ className: 'text-xs-center alert alert-danger' },
										this.state.error
									),
									_react2.default.createElement(
										'form',
										null,
										_react2.default.createElement(
											'div',
											{ className: 'form-group row' },
											_react2.default.createElement(
												'label',
												{ className: 'col-md-4 col-form-label' },
												'E-Mail Address'
											),
											_react2.default.createElement(
												'div',
												{ className: 'col-md-8' },
												_react2.default.createElement('input', { ref: 'email', type: 'email', className: 'form-control', placeholder: 'Email address' })
											)
										),
										_react2.default.createElement(
											'div',
											{ className: 'form-group row' },
											_react2.default.createElement(
												'label',
												{ className: 'col-md-4 col-form-label' },
												'Password'
											),
											_react2.default.createElement(
												'div',
												{ className: 'col-md-8' },
												_react2.default.createElement('input', { ref: 'password', type: 'password', className: 'form-control', placeholder: 'Password' })
											)
										),
										_react2.default.createElement(
											'div',
											{ className: 'form-group row' },
											_react2.default.createElement(
												'div',
												{ className: 'col-md-8 offset-md-4' },
												_react2.default.createElement(
													'div',
													{ className: 'checkbox' },
													_react2.default.createElement(
														'label',
														null,
														_react2.default.createElement('input', { type: 'checkbox', name: 'remember' }),
														' Remember Me'
													)
												)
											)
										),
										_react2.default.createElement(
											'div',
											{ className: 'form-group row' },
											_react2.default.createElement(
												'div',
												{ className: 'col-md-8 offset-md-4' },
												_react2.default.createElement(
													'button',
													{ className: 'btn btn-primary', onClick: this.handleLogin.bind(this) },
													_react2.default.createElement('i', { className: 'fa m-r-xs fa-sign-in' }),
													' Login'
												),
												_react2.default.createElement(
													_reactRouter.Link,
													{ className: 'btn btn-link', to: '/forgottenpassword' },
													'Forgot Your Password?'
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

	return Login;
}(_react.Component);

Login.contextTypes = { store: _react2.default.PropTypes.object };

module.exports = (0, _reactRouter.withRouter)(Login);