'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Http = require('./Http');

var _Http2 = _interopRequireDefault(_Http);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Auth = function () {
  function Auth() {
    _classCallCheck(this, Auth);
  }

  _createClass(Auth, [{
    key: 'login',
    value: function login(email, pass, that) {

      return _Http2.default.post('/data/auth', {
        email: email,
        password: pass
      }).then(function (data) {

        if (data.user && data.user.id) {

          that.store.dispatch({

            type: 'STORE_USER',
            user: data.user

          });
        }

        return data;
      }).then(function (data) {
        if (!data.user || !data.user.id) return that.setState({ error: 'Your email address and password were not recognised, please check and try again.' });

        var location = that.props.location;


        if (location.state && location.state.nextPathname) {
          that.props.router.replace(location.state.nextPathname);
        } else {
          that.props.router.replace('/home');
        }
      });
    }
  }, {
    key: 'getToken',
    value: function getToken() {

      //    window.reduxStore.getState().user;

      //    if (localStorage.login) {
      //      return localStorage.login.token;
      //    }

    }
  }, {
    key: 'logout',
    value: function logout(cb) {

      window.reduxStore.dispatch({

        type: 'STORE_USER',
        user: {}

      });

      if (cb) cb();
      this.onChange(false);

      return _Http2.default.get('/data/auth/logout');
    }
  }, {
    key: 'loggedIn',
    value: function loggedIn() {

      var user = window.reduxStore.getState().user;

      return !!user.id;
    }
  }, {
    key: 'onChange',
    value: function onChange() {}
  }, {
    key: 'redirectToLogin',
    value: function redirectToLogin(nextState, replace) {

      if (!new Auth().loggedIn()) {
        replace({
          pathname: '/login',
          state: { nextPathname: nextState.location.pathname }
        });
      }
    }
  }, {
    key: 'resetPassword',
    value: function resetPassword() {

      var cb = arguments[arguments.length - 1];

      return cb(true);
    }
  }]);

  return Auth;
}();

module.exports = Auth;