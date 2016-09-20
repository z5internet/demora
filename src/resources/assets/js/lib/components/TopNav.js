'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactRouter = require('react-router');

var _redexConnect = require('../utils/redex-connect');

var _redexConnect2 = _interopRequireDefault(_redexConnect);

var _Image = require('../utils/Image');

var _Image2 = _interopRequireDefault(_Image);

var _TopNavBarLeft = require('../../../../../../../../../resources/assets/react-app/components/TopNavBarLeft');

var _TopNavBarLeft2 = _interopRequireDefault(_TopNavBarLeft);

var _TopNavBarRight = require('../../../../../../../../../resources/assets/react-app/components/TopNavBarRight');

var _TopNavBarRight2 = _interopRequireDefault(_TopNavBarRight);

var _RightDropDown = require('../../../../../../../../../resources/assets/react-app/components/RightDropDown');

var _RightDropDown2 = _interopRequireDefault(_RightDropDown);

var _reactstrap = require('reactstrap');

var _reactToastr = require('react-toastr');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var dark = 'hsl(200, 20%, 20%)';
var light = '#fff';
var styles = {};

styles.link = {
  fontWeight: 200
};

styles.activeLink = _extends({}, styles.link, {
  background: light,
  color: dark
});

var TopNav = function (_Component) {
  _inherits(TopNav, _Component);

  function TopNav(props, context) {
    _classCallCheck(this, TopNav);

    var _this = _possibleConstructorReturn(this, (TopNav.__proto__ || Object.getPrototypeOf(TopNav)).call(this, props, context));

    _this.toggle = _this.toggle.bind(_this);
    _this.state = {
      dropdownOpen: false
    };

    _this.store = context.store;

    return _this;
  }

  _createClass(TopNav, [{
    key: 'componentWillReceiveProps',
    value: function componentWillReceiveProps(newProps) {

      var toastr = newProps.toastr;

      if (toastr.length > 0) {

        var state = 'success';

        if (toastr[2] && toastr[2].state) {
          state = toastr[2].state;
        }

        this.refs.container[state](toastr[0], toastr[1], toastr[2]);

        this.store.dispatch({

          type: 'TOASTR',
          message: []

        });
      }
    }
  }, {
    key: 'toggle',
    value: function toggle() {
      this.setState({
        dropdownOpen: !this.state.dropdownOpen
      });
    }
  }, {
    key: 'render',
    value: function render() {
      var user = this.props.user;


      var ToastMessageFactory = _react2.default.createFactory(_reactToastr.ToastMessage.animation);

      return user && user.id ? _react2.default.createElement(
        _reactstrap.Navbar,
        { className: 'navbarTop', fixed: 'top' },
        _react2.default.createElement(
          'div',
          { className: 'container' },
          _react2.default.createElement(
            'button',
            { className: 'navbar-toggler hidden-sm-up', type: 'button', 'data-toggle': 'collapse', 'data-target': '#exCollapsingNavbar2' },
            '☰'
          ),
          _react2.default.createElement(
            'div',
            { className: 'collapse navbar-toggleable-xs' },
            _react2.default.createElement(
              _reactstrap.NavbarBrand,
              { href: '/' },
              'Brand'
            ),
            document.location.pathname.match(/\/getStarted/) ? '' : _react2.default.createElement(
              'div',
              null,
              _TopNavBarLeft2.default ? _react2.default.createElement(_TopNavBarLeft2.default, null) : '',
              _react2.default.createElement(
                _reactstrap.Nav,
                { className: 'pull-xs-right', navbar: true },
                _react2.default.createElement(
                  _reactstrap.NavItem,
                  null,
                  _react2.default.createElement(
                    'a',
                    { className: 'nav-link has-activity-indicator' },
                    _react2.default.createElement(
                      'div',
                      { className: 'navbar-icon' },
                      _react2.default.createElement('i', { className: 'activity-indicator' }),
                      _react2.default.createElement('i', { className: 'icon fa fa-bell' })
                    )
                  )
                ),
                _react2.default.createElement(
                  _reactstrap.NavItem,
                  null,
                  _react2.default.createElement(
                    _reactRouter.Link,
                    { className: 'nav-link', style: styles.link, to: '/profile' },
                    user.first_name
                  )
                ),
                _react2.default.createElement(
                  _reactstrap.NavDropdown,
                  { isOpen: this.state.dropdownOpen, toggle: this.toggle, className: 'profile_image' },
                  _react2.default.createElement(
                    _reactstrap.DropdownToggle,
                    { caret: true },
                    _react2.default.createElement(
                      _reactstrap.NavLink,
                      { href: '#' },
                      _react2.default.createElement('img', { src: (0, _Image2.default)(user.image, 50) })
                    )
                  ),
                  _react2.default.createElement(_RightDropDown2.default, { links: user.menu })
                )
              ),
              _TopNavBarRight2.default ? _react2.default.createElement(_TopNavBarRight2.default, null) : ''
            )
          )
        ),
        _react2.default.createElement(_reactToastr.ToastContainer, { ref: 'container',
          toastMessageFactory: ToastMessageFactory,
          className: 'toast-bottom-left' })
      ) : _react2.default.createElement(
        _reactstrap.Navbar,
        { className: 'navbarTop', fixed: 'top' },
        _react2.default.createElement(
          'button',
          { className: 'navbar-toggler hidden-sm-up', type: 'button', 'data-toggle': 'collapse', 'data-target': '#exCollapsingNavbar2' },
          '☰'
        ),
        _react2.default.createElement(
          _reactstrap.NavbarBrand,
          { href: '/' },
          'Brand'
        ),
        _react2.default.createElement(
          _reactstrap.Nav,
          { className: 'pull-xs-right', navbar: true },
          _react2.default.createElement(
            _reactstrap.NavItem,
            null,
            _react2.default.createElement(
              _reactRouter.Link,
              { className: 'nav-link', style: styles.link, to: '/signup' },
              'Signup'
            )
          ),
          _react2.default.createElement(
            _reactstrap.NavItem,
            null,
            _react2.default.createElement(
              _reactRouter.Link,
              { className: 'nav-link', style: styles.link, to: '/Login' },
              'Login'
            )
          )
        )
      );
    }
  }]);

  return TopNav;
}(_react.Component);

module.exports = (0, _redexConnect2.default)(TopNav, {
  user: 'user',
  website: 'website',
  router: 'router',
  toastr: 'toastr'
});