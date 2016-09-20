'use strict';

module.exports = {
  childRoutes: [{ path: '/contact',
    getComponent: function getComponent(nextState, cb) {
      require.ensure([], function (require) {
        cb(null, require('./components/Contact'));
      });
    }
  }]
};