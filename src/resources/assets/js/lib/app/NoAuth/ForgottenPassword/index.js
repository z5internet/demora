'use strict';

module.exports = {
  childRoutes: [{ path: '/forgottenpassword',
    getComponent: function getComponent(nextState, cb) {
      require.ensure([], function (require) {
        cb(null, require('./components/ForgottenPassword'));
      });
    }
  }, { path: '/resetpassword',
    getComponent: function getComponent(nextState, cb) {
      require.ensure([], function (require) {
        cb(null, require('./components/ResetPassword'));
      });
    }
  }]
};