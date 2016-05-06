'use strict';

angular.module('app')
.directive('appHeader', function(appdir, appname) {
    return {
        restrict: 'E',
        scope: {
            'appname' : '=?'
        },
        controller: function($scope, $rootScope, $state) {

            $scope.appname = appname;

            $scope.menuLeft = {
                'Home': '#/home'
            };

            $scope.menuRight = {
                'Administration': {
                    link: '#/admin/users',
                    subItems: {
                        'Users': '#/admin/users',
                        'Groups': '#/admin/groups'
                    }
                },
                'Logout': '/auth/logout'
            };

            $scope.hasRight = function(state) {
                return $scope.hasRightToAccessState($state.get(state));
            };

        },
        templateUrl: appdir + '/shared/header/header.html'
    };
});
