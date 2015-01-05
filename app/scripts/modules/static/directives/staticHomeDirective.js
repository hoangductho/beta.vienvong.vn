'use strict';

/**
 * Created by hoanggia on 1/2/15.
 */

angular
    .module('static')
    .directive('staticHomeDirective', function ($window) {
        return {
            restrict: 'A',
            link: function ($scope, $element) {
                //console.log($scope);

                 function listPostRender(listPost) {
                    var chatViewer = $element.text(null);

                    angular.forEach(listPost, function(post, index){
                        var render = chatViewer.append(
                            '<div class="post container-fluid none-space" ng-repeat="post in listPost">'+
                        '<div class="post-avatar col-lg-5 col-md-12 col-sm-5 none-space">'+
                        '<div class="avatar col-lg-12 none-space">'+
                        '<img src="{{post.avatar}}">'+
                        '</div>'+
                        '</div>'+
                        '<div class="post-description col-lg-7 col-md-12 col-sm-7">'+
                        '<div class="post-title">'+
                        '<a href="/view/{{post.id}}#{{post.friendly_title}}">'+
                        '<p>{{post.title}}</p>'+
                    '</a>'+
                    '</div>'+
                    '<div class="post-info">'+
                     '<p>{{post.time}}</p>'+
                '</div>'+
                '<div class="post-social">'+

                '</div>'+
                '</div>'+
                '</div>'
                        );
                    });
                };

                $scope.$watch('listPost', listPostRender($scope.listPost));
            }
        }
    });
