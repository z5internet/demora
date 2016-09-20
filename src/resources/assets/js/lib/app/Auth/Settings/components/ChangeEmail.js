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

var ChangeEmail = function (_React$Component) {
	_inherits(ChangeEmail, _React$Component);

	function ChangeEmail(props, context) {
		_classCallCheck(this, ChangeEmail);

		var _this = _possibleConstructorReturn(this, (ChangeEmail.__proto__ || Object.getPrototypeOf(ChangeEmail)).call(this, props, context));

		_this.state = {

			error: '',
			html: _this.defaultRender()

		};

		_this.store = _this.context.store;

		return _this;
	}

	_createClass(ChangeEmail, [{
		key: 'defaultRender',
		value: function defaultRender() {

			return _react2.default.createElement(
				'div',
				{ className: 'text-xs-center' },
				_react2.default.createElement(
					'h4',
					null,
					'Checking your verification link'
				),
				_react2.default.createElement('div', { className: 'fa fa-2x fa-cog fa-spin' })
			);
		}
	}, {
		key: 'componentWillReceiveProps',
		value: function componentWillReceiveProps(newProps) {

			this.setState({

				html: this.defaultRender()

			});
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var data = {
				emailChange: 1,
				id: this.props.location.query.id,
				code: this.props.location.query.code,
				email: this.props.location.query.email
			};

			_Http2.default.put('/data/settings', {

				settings: data

			}).then(function (data) {

				var html = _react2.default.createElement(
					'div',
					{ className: 'alert alert-danger text-xs-center' },
					'We could not verify your email address. This is probably because your email address has already been verified.'
				);

				if (!data.ecError && data.user) {

					_this2.store.dispatch({

						type: 'STORE_USER',
						user: data.user

					});

					html = 'Your email address has been verified.';
				}

				_this2.setState({

					html: _react2.default.createElement(
						'div',
						null,
						html
					)

				});
			});
		}
	}, {
		key: 'render',
		value: function render() {

			return this.state.html;
		}
	}]);

	return ChangeEmail;
}(_react2.default.Component);

ChangeEmail.contextTypes = { store: _react2.default.PropTypes.object };

module.exports = ChangeEmail;