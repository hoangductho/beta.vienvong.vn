'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('auth')
    .controller('authMainCtrl', function ($scope, $rootScope) {
        $scope.login = function() {
            $rootScope.online = true;
        };

        $scope.logout = function() {
            $rootScope.online = false;
        }
    });
