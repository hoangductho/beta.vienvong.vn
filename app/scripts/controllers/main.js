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

        var newPosts = [
            {
                'id': '5',
                'title': 'Microsoft xóa tên Nokia khỏi Lumia',
                'friendly_title': 'microsoft-xoa-ten-nokia-khoi-lumia',
                'avatar': 'http://img.v3.news.zdn.vn/Uploaded/OFH_oazszstq/2014_06_16/nokiamicrosoftlumia10201024x691.jpg',
                'time': '03-01-2015 07:35'
            },
            {
                'id': '6',
                'title': 'Nokia thuê Foxconn gia công thiết bị mới',
                'friendly_title': 'nokia-thue-foxconn-gia-cong-thiet-bi-moi',
                'avatar': 'http://xahoithongtin.com.vn/files/baovg/video-undefined-23438C0800000578-482_636x358.jpg',
                'time': '04-01-2015 06:44'
            },
            {
                'id': '7',
                'title': 'Nokia có vi phạm thỏa thuận với Microsfot?',
                'friendly_title': 'nokia-co-vi-pham-thoa-thuan-voi-microsoft',
                'avatar': 'http://baodautu.vn/stores/news_dataimages/chicong/032014/04/09/ttg-aithangtrongthoathuanmicrosoftnokia-1-1.jpg',
                'time': '05-01-2015 9:52'
            }
        ];

        $scope.searchPosts = function() {
            console.log('searching...')
            $scope.listPost = newPosts;
        }
    });
