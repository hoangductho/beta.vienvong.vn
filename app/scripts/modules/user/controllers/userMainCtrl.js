'use strict';
/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('user')
    .controller('userMainCtrl', function ($scope, $rootScope, $location) {
        if(!$rootScope.online) {
            $location.path('/home');
            $rootScope.option = 'loginBoxShow'
        }

        var modulePath = '/scripts/modules/user/';
        $scope.userMenuPath = modulePath + 'views/userMenu.html';
    });
