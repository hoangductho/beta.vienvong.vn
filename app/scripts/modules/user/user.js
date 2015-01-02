'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('user', [
        'ui.router',
        'markdown',
        'offClick'
    ])
    .config(function ($stateProvider) {

        var modulePath = 'scripts/modules/user/';

        //$urlRouterProvider.otherwise("/profile");

        $stateProvider
            .state('root.user', {
                abstract: true,
                templateUrl: modulePath + 'views/user_template.html'
            })
            .state('root.user.profile', {
                url:'/profile',
                templateUrl: modulePath + 'views/profile.html'
            })
            .state('root.user.write', {
                url:'/write',
                templateUrl: modulePath + 'views/write.html'
            });
    });
