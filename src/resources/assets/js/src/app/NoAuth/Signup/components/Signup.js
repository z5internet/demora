'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _Http = require('../../../../utils/Http');

var _Http2 = _interopRequireDefault(_Http);

var _redexConnect = require('../../../../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Signup = function (_Component) {
	_inherits(Signup, _Component);

	function Signup(props) {
		_classCallCheck(this, Signup);

		var _this = _possibleConstructorReturn(this, (Signup.__proto__ || Object.getPrototypeOf(Signup)).call(this, props));

		_this.state = {

			first_name: '',
			email: '',
			joined: false

		};

		return _this;
	}

	_createClass(Signup, [{
		key: 'onChangeFirstName',
		value: function onChangeFirstName() {

			this.setState({
				first_name: this.refs.first_name.value
			});
		}
	}, {
		key: 'onChangeEmail',
		value: function onChangeEmail() {

			this.setState({
				email: this.refs.email.value
			});
		}
	}, {
		key: 'onSubmit',
		value: function onSubmit() {
			var _this2 = this;

			var d = {
				first_name: this.state.first_name,
				email: this.state.email
			};

			_Http2.default.post('/data/join', d).then(function (data) {

				if (data.joined) {

					_this2.setState({
						joined: 1
					});
				}
			});
		}
	}, {
		key: 'joinAgain',
		value: function joinAgain() {

			this.setState({
				joined: false
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
						this.state.joined ? _react2.default.createElement(
							'div',
							{ className: 'text-xs-center margin-auto', style: { maxWidth: '600px' } },
							'An email has been sent to you at',
							_react2.default.createElement(
								'h1',
								null,
								this.state.email
							),
							_react2.default.createElement(
								'span',
								{ style: { color: '#ac0101', fontWeight: 'bold', fontSize: '1.2em' } },
								'If this email is incorrect, '
							),
							_react2.default.createElement(
								'a',
								{ href: '#', onClick: this.joinAgain.bind(this) },
								'click here to enter your details again.'
							),
							'.',
							_react2.default.createElement('br', null),
							_react2.default.createElement('br', null),
							'If you do not receive an email within 2 minutes, check your spam/junk folder.',
							_react2.default.createElement('br', null),
							_react2.default.createElement('br', null),
							this.state.email.match(/hotmail/) || this.state.email.match(/live/) ? _react2.default.createElement(
								'div',
								{ style: { color: '#ac0101' } },
								_react2.default.createElement(
									'b',
									null,
									'PLEASE NOTE:'
								),
								' Due to a recent update to Microsoft Hotmail our emails may end up in your spam or junk folder. To prevent this please add ',
								this.props.website.name,
								' to your safe senders list.'
							) : ''
						) : _react2.default.createElement(
							'div',
							{ className: 'card' },
							_react2.default.createElement(
								'div',
								{ className: 'card-header' },
								'Signup'
							),
							_react2.default.createElement(
								'div',
								{ className: 'card-block' },
								_react2.default.createElement(
									'div',
									{ className: 'card-text' },
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
											_react2.default.createElement('input', { ref: 'first_name', type: 'text', className: 'form-control', placeholder: 'First name', onChange: this.onChangeFirstName.bind(this) })
										)
									),
									_react2.default.createElement(
										'div',
										{ className: 'row form-group' },
										_react2.default.createElement(
											'label',
											{ className: 'col-md-4 col-form-label' },
											'Email'
										),
										_react2.default.createElement(
											'div',
											{ className: 'col-md-8' },
											_react2.default.createElement('input', { ref: 'email', type: 'text', className: 'form-control', placeholder: 'Email address', onChange: this.onChangeEmail.bind(this) })
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
												{ className: 'btn btn-primary', onClick: this.onSubmit.bind(this) },
												'Join'
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

	return Signup;
}(_react.Component);

module.exports = (0, _redexConnect2.default)(Signup, {
	website: 'website'
});