'use strict';

module.exports = {
  childRoutes: [{ path: '/privacy',
    getComponent: function getComponent(nextState, cb) {
      require.ensure([], function (require) {
        cb(null, require('../../../../../../../../../../../resources/assets/react-app/components/Privacy'));
      });
    }
  }, { path: '/terms',
    getComponent: function getComponent(nextState, cb) {
      require.ensure([], function (require) {
        cb(null, require('../../../../../../../../../../../resources/assets/react-app/components/Terms'));
      });
    }
  }]
};