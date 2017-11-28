"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,a,r){return a&&e(t.prototype,a),r&&e(t,r),t}}(),_extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var r in a)Object.prototype.hasOwnProperty.call(a,r)&&(e[r]=a[r])}return e},_react=require("react"),_react2=_interopRequireDefault(_react),_reactRouterDom=require("react-router-dom"),_reduxConnect=require("rufUtils/redux-connect"),_reduxConnect2=_interopRequireDefault(_reduxConnect),_TopNavBarLogo=require("../../../../../../../../../resources/assets/react-app/components/TopNavBarLogo"),_TopNavBarLogo2=_interopRequireDefault(_TopNavBarLogo),_TopNavBarLeft=require("../../../../../../../../../resources/assets/react-app/components/TopNavBarLeft"),_TopNavBarLeft2=_interopRequireDefault(_TopNavBarLeft),_TopNavBarRight=require("../../../../../../../../../resources/assets/react-app/components/TopNavBarRight"),_TopNavBarRight2=_interopRequireDefault(_TopNavBarRight),_RightDropDown=require("./RightDropDown"),_RightDropDown2=_interopRequireDefault(_RightDropDown),_fontAwesome=require("font-awesome/css/font-awesome.css"),_fontAwesome2=_interopRequireDefault(_fontAwesome),_reactstrap=require("reactstrap"),_reactToastr=require("react-toastr"),_NotificationsDropDown=require("./Notifications-Drop-Down"),_NotificationsDropDown2=_interopRequireDefault(_NotificationsDropDown),dark="hsl(200, 20%, 20%)",light="#fff",styles={};styles.link={fontWeight:200},styles.activeLink=_extends({},styles.link,{background:light,color:dark});var TopNav=function(e){function t(e,a){_classCallCheck(this,t);var r=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e,a));return r.toggle=r.toggle.bind(r),r.toggleNavbar=r.toggleNavbar.bind(r),r.state={dropdownOpen:!1,collapsed:!0,notifPos:0},r.store=a.store,r.collapseOpening=r.collapseOpening.bind(r),r.collapseExited=r.collapseExited.bind(r),r}return _inherits(t,e),_createClass(t,[{key:"componentWillReceiveProps",value:function(e){var t=e.toastr;if(t.length>0){var a="success";t[2]&&t[2].state&&(a=t[2].state),this.refs.container[a](t[0],t[1],t[2]),this.store.dispatch({type:"TOASTR",message:[]})}this.props=e}},{key:"componentDidMount",value:function(){window.addEventListener("resize",this.toggleNavbarClose.bind(this))}},{key:"toggleNavbarClose",value:function(){this.setState({collapsed:!0})}},{key:"toggle",value:function(){this.setState({dropdownOpen:!this.state.dropdownOpen})}},{key:"toggleNavbar",value:function(){this.setState({collapsed:!this.state.collapsed})}},{key:"closeNavbar",value:function(){this.setState({collapsed:!0})}},{key:"collapseOpening",value:function(){this.setState({notifPos:1})}},{key:"collapseExited",value:function(){this.setState({notifPos:0})}},{key:"render",value:function(){var e=this.props,t=e.user,a=e.website,r=e.location,o=_react2.default.createFactory(_reactToastr.ToastMessage.animation),n=this;return t&&t.id?r.pathname.match(/^\/getStarted/)?_react2.default.createElement(_reactstrap.Navbar,{className:"navbarTop navbar-dark",fixed:"top"},_react2.default.createElement("div",null," ")):_react2.default.createElement(_reactstrap.Navbar,{className:"navbarTop",dark:!0,fixed:"top",style:{zIndex:999}},_react2.default.createElement(_TopNavBarLogo2.default,null),_react2.default.createElement(_TopNavBarLeft2.default,{closeNavbar:this.closeNavbar.bind(this)}),_react2.default.createElement("div",{style:{flexGrow:2}}),_react2.default.createElement(_TopNavBarRight2.default,{closeNavbar:n.closeNavbar.bind(n)}),_react2.default.createElement(_reactstrap.Nav,null,_react2.default.createElement(_NotificationsDropDown2.default,null)),_react2.default.createElement(_reactstrap.Nav,null,t.multiAccounts&&Object.keys(t.multiAccounts).length>1?_react2.default.createElement(_reactstrap.NavItem,{className:"d-none d-md-inline-block"},_react2.default.createElement(_reactRouterDom.Link,{className:"nav-link",to:"/settings/multiAccounts/"+t.currentTeam.id},"(",t.multiAccounts[t.currentTeam.id].name,")")):""),_react2.default.createElement(_reactstrap.Nav,null,_react2.default.createElement(_reactstrap.Dropdown,{nav:!0,isOpen:this.state.dropdownOpen,toggle:this.toggle,className:"profile_image",style:{lineHeight:"38px"}},_react2.default.createElement(_reactstrap.DropdownToggle,{tag:"a",style:{color:"#fff"}},_react2.default.createElement(_reactstrap.NavbarToggler,{onClick:this.toggleNavbar,style:{padding:"0.25rem"}})),_react2.default.createElement(_RightDropDown2.default,{links:a.menu}))),_react2.default.createElement(_reactToastr.ToastContainer,{ref:"container",toastMessageFactory:o,className:"toast-bottom-left"})):_react2.default.createElement(_reactstrap.Navbar,{className:"navbarTop topBarLoggedOut",dark:!0,fixed:"top"},_react2.default.createElement("div",{style:{flexGrow:2}},_react2.default.createElement(_TopNavBarLogo2.default,null)),_react2.default.createElement(_reactstrap.Nav,null,a.signups?_react2.default.createElement(_reactstrap.NavItem,null,_react2.default.createElement(_reactRouterDom.Link,{className:"nav-link",style:styles.link,to:"/signup"},"Signup")):"",_react2.default.createElement(_reactstrap.NavItem,null,_react2.default.createElement(_reactRouterDom.Link,{className:"nav-link",style:styles.link,to:"/login"},"Login"))))}}]),t}(_react.Component);module.exports=(0,_reactRouterDom.withRouter)((0,_reduxConnect2.default)(TopNav,{user:"user",website:"website",toastr:"toastr"}));