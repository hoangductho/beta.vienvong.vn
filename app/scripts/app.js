'use strict';

/**
 * @ngdoc overview
 * @name angularApp
 * @description
 * # angularApp
 *
 * Main module of the application.
 */
angular
    .module('angularApp', [
        'ngAnimate',
        'ngCookies',
        'ngResource',
        'ngRoute',
        'ngSanitize',
        'ngTouch',
        'markdown',
        'Facebook',
        'ui.router',
        'offClick'
    ])
    .config(function ($stateProvider, $urlRouterProvider, $locationProvider) {

        $urlRouterProvider.otherwise("/");

        $stateProvider
            .state('root', {
                abstract: true,
                templateUrl: 'views/template.html',
                controller: 'MainCtrl'
            })
            .state('root.home', {
                url:'/',
                templateUrl: 'views/home.html'
            })
            .state('root.view', {
                url:'/view',
                templateUrl: 'views/view.html'
            })
            .state('root.write', {
                url:'/write',
                templateUrl: 'views/write.html'
            })
            .state('root.profile', {
                url:'/profile',
                templateUrl: 'views/profile.html'
            })
            .state('root.registry', {
                url:'/registry',
                templateUrl: 'views/registry.html'
            })
            .state('root.support', {
                url:'/support',
                templateUrl: 'views/support.html'
            });

        $locationProvider.html5Mode(true);

        $(document).ajaxComplete(function () {
            console.log('FB re-parse');
            try {
                FB.XFBML.parse();
            } catch (ex) {
            }
        });
    });
