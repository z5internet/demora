'use strict';

module.exports = {
  path: '/getStarted',

  getComponent: function getComponent(nextState, cb) {
    require.ensure([], function (require) {
      cb(null, require('../../../../../../../../../../../resources/assets/react-app/components/GetStarted'));
    }, 'auth/home');
  }
};