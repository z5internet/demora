"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}Object.defineProperty(exports,"__esModule",{value:!0});var _createClass=function(){function e(e,t){for(var r=0;r<t.length;r++){var a=t[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(t,r,a){return r&&e(t.prototype,r),a&&e(t,a),t}}(),_react=require("react"),_react2=_interopRequireDefault(_react),_reactRouterDom=require("react-router-dom"),_FooterNavBar=require("../../../../../../../../../resources/assets/react-app/components/FooterNavBar"),_FooterNavBar2=_interopRequireDefault(_FooterNavBar),_reactstrap=require("reactstrap"),Footer=function(e){function t(e,r){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e,r));return a.state={collapsed:!0},a}return _inherits(t,e),_createClass(t,[{key:"toggleNavbar",value:function(){this.setState({collapsed:!this.state.collapsed})}},{key:"closeNavbar",value:function(){this.setState({collapsed:!0})}},{key:"render",value:function(){var e=this.props,t=(e.user,e.location);return _react2.default.createElement(_reactstrap.Navbar,{className:"navbarBottom",expand:"md",dark:!0},_react2.default.createElement(_reactstrap.NavbarToggler,{onClick:this.toggleNavbar.bind(this)}),_react2.default.createElement(_reactstrap.Collapse,{navbar:!0,isOpen:!this.state.collapsed},t.pathname.match(/^\/getStarted/)?_react2.default.createElement("ul",{className:"nav navbar-nav"},_react2.default.createElement("li",null," ")):_FooterNavBar2.default?_react2.default.createElement(_FooterNavBar2.default,{closeNavBar:this.closeNavbar.bind(this)}):""))}}]),t}(_react.Component);exports.default=(0,_reactRouterDom.withRouter)(Footer);