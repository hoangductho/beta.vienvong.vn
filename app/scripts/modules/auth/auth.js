'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('auth', [
        'ui.router',
        'markdown',
        'offClick'
    ])
    .config(function ($stateProvider) {

        var modulePath = 'scripts/modules/auth/';

        //$urlRouterProvider.otherwise("/registry");

        $stateProvider
            .state('root.auth', {
                abstract: true,
                templateUrl: modulePath + 'views/auth_template.html'
            })
            .state('root.auth.registry', {
                url:'/registry',
                templateUrl: modulePath + 'views/registry.html'
            });
    });
