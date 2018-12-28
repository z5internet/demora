"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,r,n){return r&&e(t.prototype,r),n&&e(t,n),t}}(),_react=require("react"),_react2=_interopRequireDefault(_react),_reduxConnect=require("rufUtils/redux-connect"),_reduxConnect2=_interopRequireDefault(_reduxConnect),_http=require("rufUtils/http"),_http2=_interopRequireDefault(_http),_propTypes=require("prop-types"),_propTypes2=_interopRequireDefault(_propTypes),_Loading=require("rufUtils/Loading"),_Loading2=_interopRequireDefault(_Loading),ChangeEmail=function(e){function t(e,r){_classCallCheck(this,t);var n=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e,r));return n.state={error:"",html:_react2.default.createElement("div",{className:"text-center"},_react2.default.createElement("h4",null,"Checking your verification link"),_react2.default.createElement(_Loading2.default,null))},n.store=n.context.store,n}return _inherits(t,e),_createClass(t,[{key:"componentDidMount",value:function(){var e=this,t=new URLSearchParams(this.props.location.search),r={emailChange:1,id:t.get("id"),code:t.get("code"),email:t.get("email")};_http2.default.put("/data/settings",{settings:r}).then(function(t){var r=_react2.default.createElement("div",{className:"alert alert-danger text-center"},"We could not verify your email address. This is probably because your email address has already been verified.");!t.ecError&&t.user&&(e.store.dispatch({type:"STORE_USER",user:t.user}),r=_react2.default.createElement("div",{className:"alert alert-primary text-center"},"Your email address has been verified.")),e.setState({html:_react2.default.createElement("div",null,r)})})}},{key:"render",value:function(){return this.state.html}}]),t}(_react2.default.Component);ChangeEmail.contextTypes={store:_propTypes2.default.object},module.exports=ChangeEmail;