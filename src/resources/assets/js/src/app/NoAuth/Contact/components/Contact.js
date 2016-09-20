'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _Http = require('../../../../utils/Http');

var _Http2 = _interopRequireDefault(_Http);

var _errorModal = require('../../../../utils/errorModal');

var _errorModal2 = _interopRequireDefault(_errorModal);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Contact = function (_Component) {
	_inherits(Contact, _Component);

	function Contact() {
		_classCallCheck(this, Contact);

		var _this = _possibleConstructorReturn(this, (Contact.__proto__ || Object.getPrototypeOf(Contact)).call(this));

		_this.state = {
			errorMessage: '',
			sent: false
		};

		return _this;
	}

	_createClass(Contact, [{
		key: 'sendMessage',
		value: function sendMessage() {
			var _this2 = this;

			var d = {
				name: this.state.name,
				email: this.state.email,
				message: this.state.message
			};

			var error = [];

			if (!d.name) {

				error.push("You need to type in your name.");
			}

			if (!d.email) {

				error.push("You need to type in your email address.");
			}

			if (!d.message) {

				error.push("You need to type in your message.");
			}

			if (error.length > 0) {

				(0, _errorModal2.default)(error.join(' '));
			} else {

				_Http2.default.post('/data/contactus', d).then(function (data) {

					if (data.success) {

						_this2.setState({
							name: '',
							email: '',
							message: '',
							sent: true
						});
					}

					if (data.error) {

						_this2.setState({ errorMessage: data.error });
					}
				});
			}
		}
	}, {
		key: 'onChangeName',
		value: function onChangeName(event) {

			this.setState({
				name: event.target.value
			});
		}
	}, {
		key: 'onChangeEmail',
		value: function onChangeEmail(event) {

			this.setState({
				email: event.target.value
			});
		}
	}, {
		key: 'onChangeMessage',
		value: function onChangeMessage(event) {

			this.setState({
				message: event.target.value
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
					this.state.sent ? _react2.default.createElement(
						'div',
						{ className: 'col-xs-12' },
						'Thank you for your message, we will reply shortly.'
					) : _react2.default.createElement(
						'div',
						{ className: 'col-xs-12' },
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ htmlFor: 'name', className: 'col-sm-2 col-form-label' },
								'Name'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-sm-10' },
								_react2.default.createElement('input', { onChange: this.onChangeName.bind(this), type: 'text', className: 'form-control', id: 'name', name: 'name', placeholder: 'First & Last Name' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ htmlFor: 'email', className: 'col-sm-2 col-form-label' },
								'Email'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-sm-10' },
								_react2.default.createElement('input', { onChange: this.onChangeEmail.bind(this), type: 'email', className: 'form-control', id: 'email', name: 'email', placeholder: 'example@domain.com' })
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'label',
								{ htmlFor: 'message', className: 'col-sm-2 col-form-label' },
								'Message'
							),
							_react2.default.createElement(
								'div',
								{ className: 'col-sm-10' },
								_react2.default.createElement('textarea', { onChange: this.onChangeMessage.bind(this), className: 'form-control', rows: '4', name: 'message' })
							)
						),
						!this.state.errorMessage ? '' : _react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'div',
								{ className: 'col-sm-10 offset-sm-2' },
								_react2.default.createElement(
									'div',
									{ className: 'alert alert-danger' },
									this.state.errorMessage
								)
							)
						),
						_react2.default.createElement(
							'div',
							{ className: 'row form-group' },
							_react2.default.createElement(
								'div',
								{ className: 'col-sm-10 offset-sm-2' },
								_react2.default.createElement('input', { onClick: this.sendMessage.bind(this), id: 'submit', name: 'submit', type: 'submit', value: 'Send', className: 'btn btn-primary' })
							)
						)
					)
				)
			);
		}
	}]);

	return Contact;
}(_react.Component);

module.exports = Contact;