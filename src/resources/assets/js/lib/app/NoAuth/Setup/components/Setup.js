'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactDom = require('react-dom');

var _Http = require('../../../../utils/Http');

var _Http2 = _interopRequireDefault(_Http);

var _gender = require('../../../../utils/gender');

var _gender2 = _interopRequireDefault(_gender);

var _reactRouter = require('react-router');

var _redexConnect = require('../../../../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

var _ProfilePic = require('../../../Auth/Settings/components/ProfilePic');

var _ProfilePic2 = _interopRequireDefault(_ProfilePic);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Setup = function (_Component) {
	_inherits(Setup, _Component);

	function Setup(props, context) {
		_classCallCheck(this, Setup);

		var _this = _possibleConstructorReturn(this, (Setup.__proto__ || Object.getPrototypeOf(Setup)).call(this, props, context));

		_this.state = {
			step: 0,
			setupData: {
				first_name: '',
				last_name: '',
				gender: '',
				password1: '',
				password2: '',
				username: '',
				id: props.location.query.id,
				code: props.location.query.code
			},
			buttons: {
				prev: 0,
				next: 0
			},
			errorMessage: '',
			checkingUsername: '',
			creatingAccount: ''
		};

		return _this;
	}

	_createClass(Setup, [{
		key: 'gotoGetstarted',
		value: function gotoGetstarted() {

			this.props.router.replace('/getStarted');
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			_Http2.default.get('/data/setup?id=' + this.props.location.query.id + '&code=' + this.props.location.query.code).then(function (data) {

				if (data.setup) {

					if (data.setup.first_name) {

						_this2.state.setupData.first_name = data.setup.first_name;

						_this2.changeStep("next");
					} else {
						if (_this2.props.user && _this2.props.user.id) {

							_this2.gotoGetstarted();
						} else {

							_this2.changeStep("prev");
						}
					}
				}
			});
		}
	}, {
		key: 'changeStep',
		value: function changeStep(way) {

			if (way == 'prev' || this.verifyStep()) {

				var a = 0;

				if (way == 'prev') {

					a = -1;
				}

				if (way == 'next') {

					a = 1;
				}

				var n = this.state.step + a;

				if (n == 4 && !this.props.website.setup.usernameRequired) {
					n = n + 1;
				}

				if (n == 6 && !this.props.website.setup.uploadProfilePic) {
					n = n + 1;
				}

				this.setState({
					step: n
				});

				this.state.step = n; // need this to ensure that setController is actioned correctly

				this.setController();
			}
		}
	}, {
		key: 'verifyStep',
		value: function verifyStep() {

			switch (this.state.step) {

				case 2:

					if (!this.state.setupData.first_name || !this.state.setupData.last_name) {

						this.setState({
							errorMessage: "You need to type in both your first and last names."
						});

						return false;
					}

					if (!_gender2.default[this.state.setupData.gender]) {

						this.setState({
							errorMessage: "You need to select your gender."
						});

						return false;
					}

					break;

				case 3:

					if (!this.state.setupData.password1 || this.state.setupData.password1 != this.state.setupData.password2) {

						this.setState({
							errorMessage: "You need to type your password in both boxes. The passwords you have typed into each box do not match."
						});

						return false;
					}

					if (this.state.setupData.password1.length < 6) {

						this.setState({
							errorMessage: "Your password must be at least 6 letters long."
						});

						return false;
					}

					break;

			}

			return true;
		}
	}, {
		key: 'setController',
		value: function setController() {

			var buttons = [false, false];

			this.setState({
				errorMessage: ''
			});

			switch (this.state.step) {

				case 1:
					/* 1 - setup instructions */

					buttons[1] = true;

					break;

				case 2:
					/*  2 - enter name */

					buttons[1] = true;

					break;

				case 3:
					/* 3 - choose password */

					buttons = [true, true];

					break;

				case 4:
					/*  4 - Choose username */

					buttons = [true];

					break;

				case 5:
					/* 5 - verify information */

					this.setState({
						showPassword: new Array(this.state.setupData.password1.length + 1).join("*")
					});

					buttons = [true];

					break;

			}

			this.setState({
				buttons: {
					prev: buttons[0],
					next: buttons[1]
				}
			});
		}
	}, {
		key: 'changeData',
		value: function changeData(t, v) {

			var d = this.state.setupData;

			if (t == 'gender') {

				d.gender = v;
			} else {

				d[t] = this.refs[t].value;
			}

			this.setState(d);
		}
	}, {
		key: 'checkUsername',
		value: function checkUsername(e) {
			var _this3 = this;

			var username = this.state.setupData.username;

			if (username) {

				if (!username.match(/[^0-9a-zA-Z]/)) {

					if (username.length >= 6) {

						this.setState({
							checkingUsername: true
						});

						_Http2.default.post("/data/setup/checkusername", { username: username }).then(function (data) {

							_this3.setState({
								checkingUsername: false
							});

							if (data.setup) {

								if (data.setup.usernameOK) {

									_this3.changeStep('next');
								} else {

									_this3.setState({
										errorMessage: data.setup.usernameError
									});
								}
							}
						});
					} else {

						this.setState({
							errorMessage: "Your username must be at least 6 letters long."
						});
					}
				} else {

					this.setState({
						errorMessage: "Your username can only contain the letters a-z and the numbers 0-9."
					});
				}
			}

			e.preventDefault();
		}
	}, {
		key: 'completeSetup',
		value: function completeSetup() {
			var _this4 = this;

			this.setState({
				creatingAccount: false
			});

			_Http2.default.post("/data/setup", this.state.setupData).then(function (data) {

				_this4.setState({
					creatingAccount: false
				});

				if (data.user && data.user.id) {

					_this4.context.store.dispatch({

						type: 'STORE_USER',
						user: data.user

					});

					_this4.changeStep("next");
				} else {

					_this4.setState({
						errorMessage: data.setup.error
					});
				}
			});
		}
	}, {
		key: 'render',
		value: function render() {

			var out = void 0;

			switch (this.state.step) {

				case 0:

					out = _react2.default.createElement(
						'div',
						{ className: 'text-xs-center' },
						_react2.default.createElement(
							'h1',
							null,
							'Checking your verification link'
						),
						_react2.default.createElement('div', { className: 'fa fa-3x fa-cog fa-spin' })
					);

					break;

				case -1:

					out = _react2.default.createElement(
						'div',
						{ className: 'text-xs-center' },
						_react2.default.createElement(
							'h2',
							{ className: 'red' },
							'This is not a valid verification link.'
						),
						'This is probably because you\'ve already validated your email address, or it could be because you haven\'t typed the link in correctly.'
					);

					break;

				case 1:

					out = _react2.default.createElement(
						'div',
						{ className: 'text-xs-center' },
						_react2.default.createElement(
							'h2',
							{ className: 'red' },
							'Verification link accepted.'
						),
						'You are 3 steps and 1 minute away from setting up your ',
						this.props.website.name,
						'  account. Click on the "Next" button to get started.'
					);

					break;

				case 2:

					out = _react2.default.createElement(
						'div',
						null,
						_react2.default.createElement(
							'h2',
							null,
							'Setup'
						),
						'First, enter your name and gender below.',
						_react2.default.createElement('br', null),
						_react2.default.createElement('br', null),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ className: 'col-md-4 col-form-label' },
								'First name'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-md-8' },
								_react2.default.createElement('input', { className: 'form-control', type: 'text', ref: 'first_name', defaultValue: this.state.setupData.first_name, onChange: this.changeData.bind(this, 'first_name'), placeholder: 'Your first name' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ className: 'col-md-4 col-form-label' },
								'Last name'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-md-8' },
								_react2.default.createElement('input', { className: 'form-control', type: 'text', ref: 'last_name', defaultValue: this.state.setupData.last_name, onChange: this.changeData.bind(this, 'last_name'), placeholder: 'Your last name' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ className: 'col-md-4 col-form-label' },
								'Gender'
							),
							_gender2.default.map(function (currentValue, index, arr) {

								return _react2.default.createElement(
									'div',
									{ key: index, className: 'col-md-3 text-xs-center' },
									_react2.default.createElement(
										'label',
										null,
										currentValue.name,
										_react2.default.createElement('br', null),
										_react2.default.createElement('input', {
											name: 'gender',
											type: 'radio',
											onClick: this.changeData.bind(this, 'gender', index),
											defaultChecked: this.state.setupData.gender === index
										})
									)
								);
							}, this)
						)
					);

					break;

				case 3:

					out = _react2.default.createElement(
						'div',
						null,
						_react2.default.createElement(
							'h2',
							null,
							'Choose a password'
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							'Now choose a password that you want to use when you login. Type your password in twice. Your password is case sensitive so be careful with capital letters.'
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ className: 'col-md-4 col-form-label' },
								'Type your password'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-md-8' },
								_react2.default.createElement('input', { className: 'form-control', type: 'password', ref: 'password1', defaultValue: this.state.setupData.password1, onChange: this.changeData.bind(this, 'password1'), placeholder: 'Type your password' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ className: 'col-md-4 col-form-label' },
								'Retype your password'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-md-8' },
								_react2.default.createElement('input', { className: 'form-control', type: 'password', ref: 'password2', defaultValue: this.state.setupData.password2, onChange: this.changeData.bind(this, 'password2'), placeholder: 'Retype your password' })
							)
						)
					);

					break;
				case 4:
					out = _react2.default.createElement(
						'div',
						null,
						_react2.default.createElement(
							'h2',
							null,
							'Choose a username'
						),
						_react2.default.createElement(
							'div',
							{ className: 'text-xs-center row form-group' },
							'Your username must be at least 6 characters long and can only contain the letters A to Z and the numbers 0 to 9.',
							_react2.default.createElement('br', null),
							_react2.default.createElement('br', null),
							_react2.default.createElement(
								'b',
								null,
								'Type your username in the box below.'
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'div',
								{ className: 'col-md-12' },
								_react2.default.createElement('input', { className: 'form-control', type: 'text', ref: 'username', defaultValue: this.state.setupData.username, onChange: this.changeData.bind(this, 'username'), placeholder: 'Type a username' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'text-xs-center' },
							_react2.default.createElement(
								'button',
								{ type: 'submit', className: 'btn btn-primary', disabled: this.state.checkingUsername, onClick: this.checkUsername.bind(this) },
								_react2.default.createElement(
									'span',
									null,
									'Check username'
								)
							)
						)
					);
					break;
				case 5:
					out = _react2.default.createElement(
						'div',
						null,
						_react2.default.createElement(
							'h2',
							null,
							'Verify your information'
						),
						_react2.default.createElement(
							'div',
							{ className: 'form-group' },
							'If the information below is correct, click "Create account".'
						),
						_react2.default.createElement(
							'ul',
							{ className: 'list-group' },
							_react2.default.createElement(
								'li',
								{ className: 'list-group-item' },
								_react2.default.createElement(
									'h4',
									{ className: 'list-group-item-heading' },
									'First name'
								),
								_react2.default.createElement(
									'p',
									{ className: 'list-group-item-text' },
									this.state.setupData.first_name
								)
							),
							_react2.default.createElement(
								'li',
								{ className: 'list-group-item' },
								_react2.default.createElement(
									'h4',
									{ className: 'list-group-item-heading' },
									'Last name'
								),
								_react2.default.createElement(
									'p',
									{ className: 'list-group-item-text' },
									this.state.setupData.last_name
								)
							),
							_react2.default.createElement(
								'li',
								{ className: 'list-group-item' },
								_react2.default.createElement(
									'h4',
									{ className: 'list-group-item-heading' },
									'Password'
								),
								_react2.default.createElement(
									'p',
									{ className: 'list-group-item-text' },
									this.state.showPassword
								)
							),
							!this.props.website.setup.usernameRequired ? '' : _react2.default.createElement(
								'li',
								{ className: 'list-group-item' },
								_react2.default.createElement(
									'h4',
									{ className: 'list-group-item-heading' },
									'Username'
								),
								_react2.default.createElement(
									'p',
									{ className: 'list-group-item-text' },
									this.state.setupData.username
								)
							),
							_react2.default.createElement(
								'li',
								{ className: 'list-group-item' },
								_react2.default.createElement(
									'h4',
									{ className: 'list-group-item-heading' },
									'Gender'
								),
								_react2.default.createElement(
									'p',
									{ className: 'list-group-item-text' },
									_gender2.default[this.state.setupData.gender].name
								)
							)
						),
						_react2.default.createElement(
							'button',
							{ className: 'btn btn-primary', disabled: this.state.creatingAccount, onClick: this.completeSetup.bind(this), style: { float: 'right' } },
							'Create account'
						)
					);
					break;

				case 6:

					out = _react2.default.createElement(
						'div',
						null,
						_react2.default.createElement(
							'div',
							{ className: 'row' },
							_react2.default.createElement(
								'div',
								{ className: 'col-xs-12', style: { marginBottom: '20px' } },
								_react2.default.createElement(
									'h2',
									null,
									'Upload a profile pic'
								),
								'If you want to upload a profile pic now you can do that below. If you\'re not ready to upload a profile pic, click on "Skip".'
							)
						),
						_react2.default.createElement(_ProfilePic2.default, {
							finished: this.gotoGetstarted.bind(this) }),
						_react2.default.createElement(
							'button',
							{ className: 'btn btn-primary pull-right', onClick: this.gotoGetstarted.bind(this) },
							'Skip'
						)
					);

					break;

			}

			return _react2.default.createElement(
				'div',
				{ style: { maxWidth: '600px', margin: '0 auto' } },
				out,
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					!this.state.errorMessage ? '' : _react2.default.createElement(
						'div',
						{ className: 'red', style: { fontWeight: 'bolder', textAlign: 'center', marginBottom: '18px' } },
						this.state.errorMessage
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-xs-6 text-xs-center' },
						this.state.buttons.prev ? _react2.default.createElement(
							'button',
							{ className: 'btn btn-primary', onClick: this.changeStep.bind(this, 'prev') },
							'Prev'
						) : ''
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-xs-6 text-xs-center' },
						this.state.buttons.next ? _react2.default.createElement(
							'button',
							{ className: 'btn btn-primary', onClick: this.changeStep.bind(this, 'next') },
							'Next'
						) : ''
					)
				)
			);
		}
	}]);

	return Setup;
}(_react.Component);

module.exports = (0, _reactRouter.withRouter)((0, _redexConnect2.default)(Setup, {
	website: 'website',
	user: 'user'
}));