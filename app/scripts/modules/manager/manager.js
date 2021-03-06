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
                templateUrl: modulePath + 'views/manager_template.html',
                controller: 'managerMainCtrl'
            })
            .state('root.manager.posts', {
                url:'/manager/posts',
                templateUrl: modulePath + 'views/posts.html',
                controller: 'managerPostCtrl'
            })
            .state('root.manager.users', {
                url:'/manager/users',
                templateUrl: modulePath + 'views/users.html',
                controller: 'managerUserCtrl'
            })
            .state('root.manager.roles', {
                url:'/manager/roles',
                templateUrl: modulePath + 'views/roles.html',
                controller: 'managerRoleCtrl'
            });
    });