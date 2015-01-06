'use strict';
/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('static')
    .controller('staticMainCtrl', function($scope){
        var modulePath = '/scripts/modules/static/';
        $scope.staticMenuPath = modulePath + 'views/staticMenu.html';
    });