'use strict';
/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('manager', [
        'ui.router',
        'markdown',
        'offClick',
        'datatables'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'scripts/modules/manager/';

        //$urlRouterProvider.otherwise("/manager/posts");

        $stateProvider
            .state('root.manager', {
                abstract: true,
                templateUrl: modulePath + 'views/manager_template.html'
            })
            .state('root.manager.posts', {
                url:'/manager/posts',
                templateUrl: modulePath + 'views/manager.html'
            })
            .state('root.manager.users', {
                url:'/manager/users',
                templateUrl: modulePath + 'views/users.html',
                controller: 'UsersCtrl'
            })
            .state('root.manager.roles', {
                url:'/manager/roles',
                templateUrl: modulePath + 'views/roles.html',
                controller: 'UsersCtrl'
            });
    });