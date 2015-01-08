'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('manager')
    .controller('managerRoleCtrl', function ($scope) {
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

        var listRole = [
            //{id: , icon: '', name: '', alias:''},
            {id: 1, icon: 'fa-user', name: 'member', alias:'Member'},
            {id: 2, icon: 'fa-pencil-square-o', name: 'writer', alias:'Writer'},
            {id: 3, icon: 'fa-users', name: 'leader', alias:'Leader'},
            {id: 4, icon: 'fa fa-book', name: 'manager', alias:'Manager'},
            {id: 5, icon: 'fa-gavel', name: 'founder', alias:'Founder'}
        ];

        $scope.listUser = listUser;

        $scope.listRole = listRole;
    });