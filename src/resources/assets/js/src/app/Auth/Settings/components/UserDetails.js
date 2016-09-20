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

var UserDetails = function (_React$Component) {
	_inherits(UserDetails, _React$Component);

	function UserDetails(props, context) {
		_classCallCheck(this, UserDetails);

		var _this = _possibleConstructorReturn(this, (UserDetails.__proto__ || Object.getPrototypeOf(UserDetails)).call(this, props, context));

		_this.state = {

			user: _this.props.user,
			error: '',
			sending: false

		};

		_this.store = _this.context.store;

		return _this;
	}

	_createClass(UserDetails, [{
		key: 'saveProfile',
		value: function saveProfile() {
			var _this2 = this;

			var k = ['first_name', 'last_name', 'email'];

			var data = {};

			this.setState({
				error: ''
			});

			var error = false;

			for (var i = 0; i < k.length; i++) {

				data[k[i]] = this.refs[k[i]].value;

				if (!data[k[i]]) {

					this.setState({
						error: 'You need to complete all the information'
					});

					error = true;
				}
			};

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

					if (data.user && data.user.id) {

						_this2.store.dispatch({

							type: 'STORE_USER',
							user: data.user

						});

						var email = _this2.refs.email.value;

						var _error = 'Your settings have been saved.';

						if (data.user.email != email) {

							_error = 'A verification email has been sent to ' + email + '. Your link will not be changed until you click on the verification in the email.';
						}

						_this2.setState({
							error: _error
						});
					}
				});
			}
		}
	}, {
		key: 'changeUserState',
		value: function changeUserState() {

			var data = {};

			for (var i in this.refs) {

				data[i] = this.refs[i].value;
			}

			this.setState({
				user: data
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var user = this.props.user;


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
						'First name'
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement('input', { ref: 'first_name', disabled: this.state.sending, type: 'text', className: 'form-control', placeholder: 'First name', value: this.state.user.first_name, onChange: this.changeUserState.bind(this) })
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
						_react2.default.createElement('input', { ref: 'last_name', disabled: this.state.sending, type: 'text', className: 'form-control', placeholder: 'Last name', value: this.state.user.last_name, onChange: this.changeUserState.bind(this) })
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
						_react2.default.createElement('input', { ref: 'email', disabled: this.state.sending, type: 'email', className: 'form-control', placeholder: 'Email address', value: this.state.user.email, onChange: this.changeUserState.bind(this) })
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
							{ className: 'btn btn-primary', disabled: this.state.sending, onClick: this.saveProfile.bind(this) },
							'Save'
						)
					)
				)
			);
		}
	}]);

	return UserDetails;
}(_react2.default.Component);

UserDetails.contextTypes = { store: _react2.default.PropTypes.object };

module.exports = (0, _redexConnect2.default)(UserDetails, {
	user: 'user'
});