'use strict';

angular.module('app')
.directive('appHeader', function(appdir, appname) {
    return {
        restrict: 'E',
        transclude: true,
        scope: {
            'appname' : '='
        },
        controller: function($scope, $rootScope, $location) {

            $scope.appname = appname;
            
            $scope.menu = { 
                'Page 1': '#1',
                'Page 2': '#2',
                'Page 3': '#3'
            };

        },
        templateUrl: appdir + '/shared/header/header.html'
    };
});