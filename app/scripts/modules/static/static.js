"use strict";
/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('static',[
        'ui.router',
        'markdown',
        'offClick'
    ])
    .config(function ($stateProvider){

        var modulePath = 'scripts/modules/static/';

        //$urlRouterProvider.otherwise("/");

        $stateProvider
            .state('root.static', {
                abstract: true,
                templateUrl: modulePath + 'views/static_template.html',
                controller: 'staticMainCtrl'
            })
            .state('root.static.home', {
                url:'/home',
                templateUrl: modulePath + 'views/home.html',
                controller: 'staticHomeCtrl'
            })
            .state('root.static.search', {
                url:'/search/:parameters',
                templateUrl: modulePath + 'views/home.html',
                controller: 'staticHomeCtrl'
            })
            .state('root.static.view', {
                url:'/view/:id',
                templateUrl: modulePath + 'views/view.html'
            });
    });