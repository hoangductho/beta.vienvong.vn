'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the angularApp
 */
angular.module('angularApp')
    .controller('MainCtrl', function ($scope, $rootScope) {
        $rootScope.option = 'searchbar';
    });
