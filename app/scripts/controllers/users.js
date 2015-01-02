/**
 * Created by hoanggia on 12/30/14.
 */

'use strict';

angular.module('angularApp')
    .controller('UsersCtrl', function ($scope,DTOptionsBuilder, DTColumnDefBuilder) {
        $scope.dtOptions = DTOptionsBuilder.newOptions()
            .withPaginationType('simple_numbers')
            .withDisplayLength(10)
            //.withDOM('pitrfl')
            .withBootstrap();

        $scope.dtColumnDefs = [
            DTColumnDefBuilder.newColumnDef(0),
            DTColumnDefBuilder.newColumnDef(1),
//                DTColumnDefBuilder.newColumnDef(1).notVisible(),
            DTColumnDefBuilder.newColumnDef(2).notSortable(),
            DTColumnDefBuilder.newColumnDef(3).notSortable(),
        ];

        var listGames = [
            {id: 1, icon: 'images/apps-big-5.png', name: 'Sugar Limo', platform: 'iOs', version: '1.2.1', status:'active'},
            {id: 2, icon: 'images/apps-big-5.png', name: 'Candy Crash', platform: 'Android', version: '2.4.2', status:'active'},
            {id: 3, icon: 'images/apps-big-5.png', name: 'Sweet Ball', platform: 'iOs', version: '1.0', status:'pending'},
            {id: 4, icon: 'images/apps-big-5.png', name: 'Color Block', platform: 'Android, iOs', version: '1.8', status:'active'},
            {id: 5, icon: 'images/apps-big-5.png', name: 'Togo Born', platform: 'Windows Phone', version: '2.6', status:'closed'},
            {id: 6, icon: 'images/apps-big-5.png', name: 'Sandy Lucy', platform: 'Android', version: '3.5', status:'closed'},
            {id: 7, icon: 'images/apps-big-5.png', name: 'Giant War', platform: 'iOs', version: '4.4', status:'active'},
            {id: 8, icon: 'images/apps-big-5.png', name: 'Class of Clan', platform: 'iOS', version: '2.1', status:'active'},
            {id: 9, icon: 'images/apps-big-5.png', name: 'Star Wars', platform: 'Windows Phone', version: '1.5', status:'closed'},
            {id: 10, icon: 'images/apps-big-5.png', name: 'Nemo Me', platform: 'iOS', version: '3.2', status:'', manager: 'active'},
            {id: 11, icon: 'images/apps-big-5.png', name: 'Hell of Troll', platform: 'Android', version: '0.9', status:'pending'},
            {id: 12, icon: 'images/apps-big-5.png', name: 'Space Black', platform: 'Android', version: '2.1.7', status:'active'},
            {id: 13, icon: 'images/apps-big-5.png', name: 'Gold Age', platform: 'iOS', version: '3.14', status:'active'},
            {id: 14, icon: 'images/apps-big-5.png', name: 'Dragon Back', platform: 'Android', version: '4.6', status:'active'},
        ];

        $scope.listGames = listGames;
    });
