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
        'offClick',
        'datatables',

        'about',
        'auth',
        'manager',
        'static',
        'user'
    ])
    .config(function ($stateProvider, $urlRouterProvider, $locationProvider) {

        $urlRouterProvider.otherwise("/");

        $stateProvider
            .state('root', {
                abstract: true,
                templateUrl: 'views/template.html',
                controller: 'MainCtrl'
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
