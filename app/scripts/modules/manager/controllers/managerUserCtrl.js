'use strict';

/**
 * Created by hoanggia on 1/8/15.
 */

angular
    .module('manager')
    .controller('managerUserCtrl', function ($scope,DTOptionsBuilder, DTColumnDefBuilder) {
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
            DTColumnDefBuilder.newColumnDef(3).notSortable()
        ];

        var listUser = [
            //{id: , uname: '', status: '', role:''},
            {id: 1, uname: 'tieugieu0990', status: 'Active', role:'Manager'},
            {id: 2, uname: 'hoangductho.3690', status: 'Active', role:'Founder'},
            {id: 3, uname: 'quachthehung', status: 'Active', role:'Member'},
            {id: 4, uname: 'caogia_1102', status: 'Pending', role:'Member'},
            {id: 5, uname: 'thegodfather', status: 'Banned', role:'Member'},
            {id: 6, uname: 'manintheblack_08', status: 'Block', role:'Member'},
            {id: 7, uname: 'sysadmin', status: 'Active', role:'Leader'},
            {id: 8, uname: 'snowdown_77', status: 'Pending', role:'Writer'}
        ];

        var listStatus = [
            //{id: , icon: '', name: '', alias: ''}
            {id: 1, icon: 'fa-pencil-square-o', name: 'pending', alias: 'Pending'},
            {id: 2, icon: 'fa-thumbs-o-up', name: 'active', alias: 'Active'},
            {id: 3, icon: 'fa-thumbs-o-down', name: 'reject', alias: 'Reject'},
            {id: 4, icon: 'fa-unlock-alt', name: 'banned', alias: 'Banned'},
            {id: 5, icon: 'fa-trash-o', name: 'block', alias: 'Block'}
        ];

        $scope.listUser = listUser;

        $scope.listUserStatus = listStatus;

        $scope.updateStatus = function(uid, status) {
            console.log('change status of '+ uid + ' to ' + status);
        }
    });