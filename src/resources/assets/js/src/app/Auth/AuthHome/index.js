'use strict';

module.exports = {
  path: '/home',

  getComponent: function getComponent(nextState, cb) {
    require.ensure([], function (require) {
      cb(null, require('../../../../../../../../../../../resources/assets/react-app/components/AuthHome'));
    }, 'auth/home');
  }
};