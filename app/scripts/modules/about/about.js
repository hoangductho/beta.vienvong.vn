'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('about', [
        'ui.router',
        'markdown',
        'offClick'
    ])
    .config(function ($stateProvider) {

        var modulePath = 'scripts/modules/about/';

        //$urlRouterProvider.otherwise("/support");

        $stateProvider
            .state('root.about', {
                abstract: true,
                templateUrl: modulePath + 'views/about_template.html'
            })
            .state('root.about.support', {
                url: '/support',
                templateUrl: modulePath + 'views/support.html'
            });
    });
