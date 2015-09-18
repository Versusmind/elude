'use strict';

angular.module('myo')
.directive('myoNavbar', function(appdir) {
    return {
        restrict: 'E',
        transclude: true,
        scope: {
            'appname': '=',
            'rawMenu': '=menu'
        },
        controller: function($scope, $rootScope, $location) {
            
            
             /**
             * generate menu from flat menu (passed in params)
             *
             *  the menu must be like this:
             *
             *    {
             *      'title1' : '#/link/to/page1',
             *      'title2' : '#/link/to/page2',
             *    }
             *
             *    Or like this:
             *    {
             *      'title1' : {
             *          link: '#/link/to/page1',
             *          subItems: {
             *            'subtitle1' : '#/link/to/page1/subtitle1',
             *            'subtitle2' : '#/link/to/page1/subtitle2'
             *          }
             *      },
             *      'title2' : '#/link/to/page2',
             *    }
             *
             **/
             console.log('scope',$scope);
            $scope.menu = [];
            _.each($scope.rawMenu, function(link, title) {

                if (_.isPlainObject(link)) {
                    //get subitems
                    var subItems = [];
                    if (link.subItems) {
                        _.each(link.subItems, function(sublink, subtitle) {
                            subItems.push({
                                title: subtitle,
                                link: sublink,
                                active: false
                            });
                        });
                    }

                    $scope.menu.push({
                        title: title,
                        link: link.link,
                        active: false,
                        subItems: subItems
                    });

                } else {
                    $scope.menu.push({
                        title: title,
                        link: link,
                        active: false
                    });
                }
            });
            
            console.log('$scope.menu',$scope.menu);
            function onLocationChange() {
                //go thru the menu and change the active param

                var url = $location.path();

                _.each($scope.menu, function(item) {
                    item.active = _.startsWith('#' + url, item.link);
                    if (item.subItems) {
                        _.each(item.subItems, function(subitem) {
                            subitem.active = _.startsWith('#' + url, subitem.link);
                        });
                    }
                });
            }

            $rootScope.$on('$locationChangeStart', function(event, newUrl, oldUrl) {
                onLocationChange();
            });

            $scope.onLinkClick = function($event) {
                //call callback if defined
                if ($scope.onClick) {
                    $scope.onClick({'hash': $event.target.hash});
                }
            };

            onLocationChange();
        
        },
        templateUrl: appdir + '/myo/components/navbar/navbar.html'
    };
});