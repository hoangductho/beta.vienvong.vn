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
                templateUrl: modulePath + 'views/static_template.html'
            })
            .state('root.static.home', {
                url:'/',
                templateUrl: modulePath + 'views/home.html'
            })
            .state('root.static.view', {
                url:'/view',
                templateUrl: modulePath + 'views/view.html'
            });
    });