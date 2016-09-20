'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactRedux = require('react-redux');

var _reactRouter = require('react-router');

var _reactstrap = require('reactstrap');

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

var _ProfilePic = require('./ProfilePic');

var _ProfilePic2 = _interopRequireDefault(_ProfilePic);

var _UserDetails = require('./UserDetails');

var _UserDetails2 = _interopRequireDefault(_UserDetails);

var _Security = require('./Security');

var _Security2 = _interopRequireDefault(_Security);

var _ChangeEmail = require('./ChangeEmail');

var _ChangeEmail2 = _interopRequireDefault(_ChangeEmail);

var _redexConnect = require('../../../../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

var _config = require('../../../../../../../../../../../../resources/assets/react-app/config');

var _config2 = _interopRequireDefault(_config);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Settings = function (_React$Component) {
	_inherits(Settings, _React$Component);

	function Settings(props) {
		_classCallCheck(this, Settings);

		var _this = _possibleConstructorReturn(this, (Settings.__proto__ || Object.getPrototypeOf(Settings)).call(this, props));

		_this.state = {
			tab: 0
		};

		_this.tabs = [{
			title: 'Your details',
			url: '',
			component: _react2.default.createElement(_UserDetails2.default, null)
		}, {
			title: 'Profile picture',
			url: 'profile',
			component: _react2.default.createElement(_ProfilePic2.default, null)
		}, {
			title: 'Security',
			url: 'security',
			component: _react2.default.createElement(_Security2.default, null)
		}, {
			title: 'Verify Email',
			url: 'changeEmail',
			dontShow: true,
			component: _react2.default.createElement(_ChangeEmail2.default, { location: _this.props.location })
		}];

		_this.tabs = _this.tabs.concat(_config2.default.settingsTabs);

		var url = _this.props.location.pathname.replace(/\/settings\/?/, '');

		for (var i in _this.tabs) {
			if (url == _this.tabs[i].url) {
				_this.state.tab = parseInt(i);
			}
		}

		return _this;
	}

	_createClass(Settings, [{
		key: 'componentWillReceiveProps',
		value: function componentWillReceiveProps(newProps) {

			var url = newProps.location.pathname.replace(/\/settings\/?/, '');

			for (var i in this.tabs) {
				if (url == this.tabs[i].url) {
					this.setState({ tab: parseInt(i) });
				}
			}
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return _react2.default.createElement(
				'div',
				{ className: 'container' },
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					_react2.default.createElement(
						'div',
						{ className: 'col-md-4' },
						_react2.default.createElement(
							'div',
							{ className: 'card' },
							_react2.default.createElement(
								'div',
								{ className: 'card-header' },
								'Settings'
							),
							_react2.default.createElement(
								'div',
								{ className: 'list-group list-group-flush' },
								this.tabs.map(function (a, i) {

									if (!a.dontShow) {

										return _react2.default.createElement(
											_reactRouter.Link,
											{ to: '/settings' + (a.url ? '/' + a.url : ''), key: i, className: (0, _classnames2.default)('list-group-item', _this2.state.tab == i ? 'active' : 'list-group-item-action') },
											a.title
										);
									}
								})
							)
						)
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-md-8' },
						_react2.default.createElement(
							'div',
							{ className: 'card' },
							_react2.default.createElement(
								'div',
								{ className: 'card-header' },
								this.tabs[this.state.tab].title
							),
							_react2.default.createElement(
								'div',
								{ className: 'card-block' },
								_react2.default.createElement(
									'div',
									{ className: 'card-text' },
									function () {
										switch (_this2.state.tab) {
											case 0:
												return _this2.tabs[0].component;

											case 1:
												return _this2.tabs[1].component;

											case 2:
												return _this2.tabs[2].component;

											case 3:

												return _this2.tabs[3].component;

										}
									}()
								)
							)
						)
					)
				)
			);
		}
	}]);

	return Settings;
}(_react2.default.Component);

module.exports = Settings;