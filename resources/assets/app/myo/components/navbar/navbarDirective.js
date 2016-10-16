'use strict';

angular.module('myo')
.directive('myoNavbar', function(appdir) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            'appname': '=',
            'rawMenu': '=menuRight',
            'rawMenuLeft': '=menuLeft'
        },
        controller: function($scope, $rootScope, $state) {

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
             *  Alternatively, you can have 2 menus (one on the left and one on the right), you need to add "menu-left"
             *  with the same parameters to your directive call
             *
             *
             **/

            $scope.menus = {
                'right': [],
                'left': []
            };
            _.each({'right': $scope.rawMenu, 'left': $scope.rawMenuLeft}, function(rawContent, menuKey) {
                if (rawContent) {
                    _.each(rawContent, function(link, title) {

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

                            $scope.menus[menuKey].push({
                                title: title,
                                link: link.link,
                                active: false,
                                subItems: subItems
                            });

                        } else {
                            $scope.menus[menuKey].push({
                                title: title,
                                link: link,
                                active: false
                            });
                        }
                    });
                }
            });

            function onLocationChange() {
                //go thru the menu and change the active param
                var url = $state.current.url;
                _.each($scope.menus, function(menu, menuKey) {
                    if (menu) {
                        _.each(menu, function(item) {
                            item.active = _.startsWith('#' + url, item.link);
                            if (item.subItems) {
                                _.each(item.subItems, function(subitem) {
                                    subitem.active = _.startsWith('#' + url, subitem.link);
                                });
                            }
                        });
                    }
                });
            }

            $rootScope.$on('$stateChangeStart', onLocationChange);
            onLocationChange();//call it on the first launch

        },
        templateUrl: appdir + '/myo/components/navbar/navbar.html'
    };
});
