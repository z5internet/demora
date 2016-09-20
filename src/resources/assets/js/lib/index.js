'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactDom = require('react-dom');

var _reactRouter = require('react-router');

var _COURSES = require('./stubs/COURSES');

var _COURSES2 = _interopRequireDefault(_COURSES);

var _rootRoutes = require('./app/rootRoutes');

var _rootRoutes2 = _interopRequireDefault(_rootRoutes);

var _fontAwesome = require('font-awesome/css/font-awesome.css');

var _fontAwesome2 = _interopRequireDefault(_fontAwesome);

var _ruf = require('../../../../../../../../resources/assets/react-app/sass/ruf.scss');

var _ruf2 = _interopRequireDefault(_ruf);

var _app = require('../../../../../../../../resources/assets/react-app/css/app.css');

var _app2 = _interopRequireDefault(_app);

var _global = require('../../css/global.css');

var _global2 = _interopRequireDefault(_global);

var _toastr = require('../../css/toastr.css');

var _toastr2 = _interopRequireDefault(_toastr);

var _animate = require('../../css/animate.css');

var _animate2 = _interopRequireDefault(_animate);

var _reactRedux = require('react-redux');

var _redux = require('redux');

var _reducers = require('./reducers');

var _reducers2 = _interopRequireDefault(_reducers);

var _Http = require('./utils/Http');

var _Http2 = _interopRequireDefault(_Http);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var App = function (_Component) {
	_inherits(App, _Component);

	function App(props, context) {
		_classCallCheck(this, App);

		var _this = _possibleConstructorReturn(this, (App.__proto__ || Object.getPrototypeOf(App)).call(this, props, context));

		var reds = _reducers2.default;

		_this.store = (0, _redux.createStore)(reds);

		window.reduxStore = _this.store;

		_this.state = {

			loaded: false

		};

		var that = _this;

		function handleLocationChange() {
			that.store.dispatch({
				type: 'ROUTER_CHANGE'
			});
		}

		_reactRouter.browserHistory.listen(handleLocationChange);

		return _this;
	}

	_createClass(App, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			_Http2.default.get('/data/start').then(function (data) {

				if (data.website) {

					_this2.store.dispatch({

						type: 'STORE_WEBSITE',

						website: data.website

					});
				}

				_this2.setState({

					loaded: 1

				});

				if (data.user) {

					_this2.store.dispatch({

						type: 'STORE_USER',

						user: data.user

					});

					if (data.user.id && !data.user.finishedGetStarted) {

						_reactRouter.browserHistory.push("/getStarted");
					}
				}
			});
		}
	}, {
		key: 'render',
		value: function render() {

			if (!this.state.loaded) {

				return _react2.default.createElement(
					'div',
					{ className: 'text-xs-center' },
					_react2.default.createElement(
						'h1',
						null,
						'Loading...'
					),
					_react2.default.createElement('div', { className: 'fa fa-3x fa-cog fa-spin' })
				);
			}

			return _react2.default.createElement(
				_reactRedux.Provider,
				{ store: this.store },
				_react2.default.createElement(_reactRouter.Router, { history: _reactRouter.browserHistory, routes: _rootRoutes2.default })
			);
		}
	}]);

	return App;
}(_react.Component);

(0, _reactDom.render)(_react2.default.createElement(App, null), document.getElementById('appRoot'));