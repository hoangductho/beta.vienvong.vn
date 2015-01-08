'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('auth')
    .controller('authMainCtrl', function ($scope, $rootScope, $location) {
        $scope.login = function() {
            $rootScope.online = true;
        };

        $scope.logout = function() {
            $rootScope.online = false;
            $location.path('/home');
        };
    });
