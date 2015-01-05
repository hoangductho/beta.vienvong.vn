'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('static')
    .controller('staticHomeCtrl', function ($scope) {
        $scope.listPost = [
            /*{
                'id': '',
                'title': '',
                'friendly_title': '',
                'avatar': '',
                'time': ''
            },*/
            {
                'id': '1',
                'title': 'Nokia N1: Xuất hiện bất ngờ',
                'friendly_title': 'nokia-n1-xuat-hien-bat-ngo',
                'avatar': 'http://xahoithongtin.com.vn/upload/2014/12/25/Nokia%20N1%20Android%20Tablet.jpg',
                'time': '22-12-2014 08:30'
            },
            {
                'id': '2',
                'title': 'Nokia C1: Nokia sẽ tái sinh với Android',
                'friendly_title': 'nokia-c1-nokia-se-tai-sinh-voi-android',
                'avatar': 'http://www.berbagiteknologi.com/wp-content/uploads/2014/12/Nokia-C1-Android.jpg',
                'time': '25-12-2014 07:44'
            },
            {
                'id': '3',
                'title': 'Những smartphone sánh ngang máy ảnh',
                'friendly_title': 'nhung-smartphone-sanh-ngang-may-anh',
                'avatar': 'http://fptshop.com.vn/Content/Images/uploaded/Phone%20IMG/Nokia/Nokia-Lumia-1020-f.jpg',
                'time': '30-12-2014 15:35'
            },
            {
                'id': '4',
                'title': 'Những smartphone đình đám sắp lên kệ',
                'friendly_title': 'nhung-smartphone-dinh-dam-sap-len-ke',
                'avatar': 'https://vtcdn.com/sites/default/files/images/2014/10/14/img-1413305642-1.jpg',
                'time': '02-01-2015 09:54'
            }
        ];

        $scope.loadMore = function() {
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

            $scope.listPost = $scope.listPost.concat(newPosts);
        }
    });
